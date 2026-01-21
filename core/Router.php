<?php

namespace Core;

use App\Http\Middlewares\Middleware;

/**
 * Router osztály - HTTP útvonalak kezelése
 * 
 * Támogatott funkciók:
 * - RESTful route-ok
 * - Resource route-ok
 * - Middleware támogatás
 * - CSRF védelem
 * - Paraméterek kezelése
 */
class Router
{
	protected $routes = [];
	protected $routes_of_resources = [
		'index'   => ['GET', ''],
		'show'    => ['GET', '/{id}'],
		'create'  => ['GET', '/create'],
		'edit'    => ['GET', '/{id}/edit'],
		'store'   => ['POST', ''],
		'update'  => ['PATCH', '/{id}'],
		'destroy' => ['DELETE', '/{id}'],
	];

	protected $exceptions = [];
	protected $csrfProtectedMethods = ['POST', 'DELETE', 'PATCH', 'PUT'];




	public function add($method, $uri, $controller)
	{
		$this->routes[] = [
			'uri' => $this->normalizeUri($uri),
			'controller' => $controller,
			'method' => strtoupper($method),
			'middleware' => null
		];

		return $this;
	}

	/**
	 * URI normalizálása
	 */
	private function normalizeUri($uri)
	{
		return '/' . trim($uri, '/');
	}

	/**
	 * Resource route-ok generálása
	 * Használat: $router->resources('users', UserController::class)
	 */


	public function apiResource($uri, $controller, $middleware = null)
	{
		if (!class_exists($controller)) {
			throw new \Exception("Controller '{$controller}' not found.");
		}

		$resourceRoutes = $this->getFilteredResourceRoutes();

		foreach ($resourceRoutes as $action => [$method, $suffix]) {
			$this->{strtolower($method)}("/api/{$uri}{$suffix}", [$controller, $action]);

			if ($middleware) {
				$this->middleware($middleware);
			}
		}

		$this->resetExceptions();
		return $this;
	}

	public function resources($uri, $controller, $middleware = null)
	{
		if (!class_exists($controller)) {
			throw new \Exception("Controller '{$controller}' not found.");
		}

		$resourceRoutes = $this->getFilteredResourceRoutes();

		foreach ($resourceRoutes as $action => [$method, $suffix]) {
			$this->{strtolower($method)}("/{$uri}{$suffix}", [$controller, $action]);

			if ($middleware) {
				$this->middleware($middleware);
			}
		}

		$this->resetExceptions();
		return $this;
	}

	/**
	 * Szűrt resource route-ok lekérése
	 */
	private function getFilteredResourceRoutes()
	{
		if (!empty($this->exceptions)) {
			return array_diff_key($this->routes_of_resources, array_flip($this->exceptions));
		}

		return $this->routes_of_resources;
	}

	/**
	 * Kivételek resetelése
	 */
	private function resetExceptions()
	{
		$this->exceptions = [];
	}

	/**
	 * Resource route-okból kivételek megadása
	 * Használat: $router->except(['create', 'edit'])->resources(...)
	 */
	public function except(array $exceptions)
	{
		$this->exceptions = $exceptions;
		return $this;
	}

	/**
	 * Csak megadott resource route-ok használata
	 * Használat: $router->just(['index', 'show'])->resources(...)
	 */
	public function just(array $actions)
	{
		$allActions = array_keys($this->routes_of_resources);
		$this->exceptions = array_diff($allActions, $actions);
		return $this;
	}

	// ================================
	// HTTP METÓDUSOK
	// ================================

	/**
	 * GET route
	 */
	public function get($uri, $controller)
	{
		return $this->add('GET', $uri, $controller);
	}

	/**
	 * POST route
	 */
	public function post($uri, $controller)
	{
		return $this->add('POST', $uri, $controller);
	}

	/**
	 * DELETE route
	 */
	public function delete($uri, $controller)
	{
		return $this->add('DELETE', $uri, $controller);
	}

	/**
	 * PATCH route
	 */
	public function patch($uri, $controller)
	{
		return $this->add('PATCH', $uri, $controller);
	}

	/**
	 * PUT route
	 */
	public function put($uri, $controller)
	{
		return $this->add('PUT', $uri, $controller);
	}


	public function apiGet($uri, $controller)
	{
		return $this->add('GET', "/api{$uri}", $controller);
	}

	public function apiPost($uri, $controller)
	{
		return $this->add('POST', "/api{$uri}", $controller);
	}

	public function apiDelete($uri, $controller)
	{
		return $this->add('DELETE', "/api{$uri}", $controller);
	}

	public function apiPatch($uri, $controller)
	{
		return $this->add('PATCH', "/api{$uri}", $controller);
	}

	public function apiPut($uri, $controller)
	{
		return $this->add('PUT', "/api{$uri}", $controller);
	}



	/**
	 * View route - közvetlenül view megjelenítésére
	 */
	public function view($uri, string $layout, string $root, array $params = [])
	{
		return $this->add('GET', $uri, function () use ($layout, $root, $params) {
			return Response::view($root, $layout, $params);
		});
	}

	/**
	 * Middleware hozzáadása az utolsó route-hoz
	 */
	public function middleware($key)
	{
		if (empty($this->routes)) {
			throw new \Exception("No routes defined to apply middleware to.");
		}

		$lastRouteIndex = array_key_last($this->routes);
		$this->routes[$lastRouteIndex]['middleware'] = $key;

		return $this;
	}


	// ================================
	// ROUTE RESOLVING
	// ================================

	/**
	 * Route feloldása és végrehajtása
	 */
	public function route($uri, $method)
	{
		$uri = $this->normalizeUri($uri);
		$method = strtoupper($method);

		foreach ($this->routes as $route) {
			if ($this->matchRoute($route, $uri, $method)) {
				$params = $this->extractParameters($route['uri'], $uri);
				$this->executeRoute($route, $params);
				return;
			}
		}

		$this->abort(404);
	}

	/**
	 * Route egyezés ellenőrzése
	 */
	private function matchRoute($route, $uri, $method)
	{
		if ($route['method'] !== $method) {
			return false;
		}

		$pattern = $this->buildRoutePattern($route['uri']);
		return preg_match($pattern, $uri);
	}

	/**
	 * Route pattern építése regex-hez
	 */
	private function buildRoutePattern($routeUri)
	{
		$pattern = preg_replace('/\{id\}/', '(\d+)', $routeUri);
		$pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
		return "#^" . $pattern . "$#";
	}

	/**
	 * Paraméterek kinyerése az URI-ból
	 */
	private function extractParameters($routeUri, $uri)
	{
		$pattern = $this->buildRoutePattern($routeUri);
		preg_match($pattern, $uri, $matches);
		array_shift($matches); // Első elem eltávolítása (teljes egyezés)
		return $matches;
	}

	/**
	 * Route végrehajtása
	 */
	private function executeRoute($route, $params)
	{
		try {
			$this->handleMiddleware($route['middleware']);
			$this->handleCsrfProtection($route['method']);
			$this->callController($route['controller'], $params);
		} finally {
			Session::unflash();
		}

		exit();
	}

	/**
	 * Middleware kezelése
	 */
	private function handleMiddleware($middleware)
	{
		if (!$middleware) {
			return;
		}

		if (is_array($middleware)) {
			foreach ($middleware as $m) {
				Middleware::resolve($m);
			}
		} else {
			Middleware::resolve($middleware);
		}
	}

	/**
	 * CSRF védelem kezelése
	 */
	private function handleCsrfProtection($method)
	{
		if (!in_array($method, $this->csrfProtectedMethods)) {
			return;
		}

		$csrf_config = require base_path('config/auth.php');

		if ($csrf_config['csrf']['protect']) {
			// TODO: CSRF ellenőrzés implementálása
			(new CSRF)->check();
			Request::unset('csrf');
			Request::unset('_method');
		}
	}

	/**
	 * Controller meghívása
	 */
	private function callController($controller, $params)
	{
		// Callable function kezelése
		if (!is_array($controller) && is_callable($controller)) {
			echo call_user_func_array($controller, $params);
			return;
		}

		// Controller osztály kezelése
		if (is_array($controller)) {
			[$controllerClass, $method] = $controller;

			if (!class_exists($controllerClass)) {
				throw new \Exception("Controller '{$controllerClass}' not found.");
			}

			if (!method_exists($controllerClass, $method)) {
				throw new \Exception("Method '{$method}' not found in controller '{$controllerClass}'.");
			}

			(new $controllerClass)->$method($params);
			unset($_SESSION['errors'], $_SESSION['old']);

			return;
		}


		throw new \Exception("Invalid controller format.");
	}

	/**
	 * HTTP hiba válasz
	 */
	protected function abort($code = 404)
	{
		http_response_code($code);
		require view_path("status/{$code}");
		die();
	}

	// ================================
	// DEBUG & UTILITY METÓDUSOK
	// ================================

	/**
	 * Összes regisztrált route listázása (debug célra)
	 */
	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * Route-ok száma
	 */
	public function getRouteCount()
	{
		return count($this->routes);
	}
}
