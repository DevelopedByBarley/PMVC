<?php

namespace Core;

use App\Http\Middlewares\Middleware;

class Router
{
	protected $routes = [];
	protected $routes_of_resources = [
		'index'   => ['GET', ''],
		'show'    => ['GET', '/{id}'],
		'create'  => ['GET', '/create'],
		'edit'    => ['GET', '/edit/{id}'],
		'store'   => ['POST', ''],
		'update'  => ['PATCH', '/{id}'],
		'destroy' => ['DELETE', '/{id}'],
	];
	protected $exceptions = [];


	public function add($method, $uri, $controller)
	{
		$this->routes[] = [
			'uri' => $uri,
			'controller' => $controller,
			'method' => $method,
			'middleware' => null
		];

		return $this;
	}

	//$route->get(/${entity}, [Controller::class, $method]);

	public function resources($uri, $controller, $middleware = null)
	{
		if (!class_exists($controller)) {
			throw new \Exception("Controller '{$controller}' not found.");
		}

		foreach ($this->routes_of_resources as $action => [$method, $suffix]) {
			if (!in_array($action, $this->exceptions)) {
				$this->{strtolower($method)}("/{$uri}{$suffix}", [$controller, $action])->middleware($middleware);
			}
		}

		$this->exceptions = [];

		return $this;
	}

	public function except(array $exceptions)
	{
		$this->exceptions = $exceptions;
		return $this;
	}
	public function just(array $exceptions)
	{

		$exceptionKeys = array_flip($exceptions);
		$this->routes_of_resources = array_intersect_key($this->routes_of_resources, $exceptionKeys);

		return $this;
	}




	public function get($uri, $controller)
	{
		return $this->add('GET', $uri, $controller);
	}

	public function post($uri, $controller)
	{
		return $this->add('POST', $uri, $controller);
	}

	public function delete($uri, $controller)
	{
		return $this->add('DELETE', $uri, $controller);
	}

	public function patch($uri, $controller)
	{
		return $this->add('PATCH', $uri, $controller);
	}

	public function put($uri, $controller)
	{
		return $this->add('PUT', $uri, $controller);
	}

	public function view($uri, string $layout, string $root, array $params = [])
	{
		return $this->add('GET', $uri, function () use ($layout, $root, $params) {
			echo view($layout, [
				'root' => view($root, $params)
			]);
			return;
		});
	}

	public function middleware($key)
	{
		$this->routes[array_key_last($this->routes)]['middleware'] = $key;
	}


	//$router->get('/admin/dashboard', [AdminController::class, 'index'])->only('admin');

	public function route($uri, $method)
	{
		// Csrf protected methods with config
		$csrfProtectedMethods = ['POST', 'DELETE', 'PATCH', 'PUT'];
		$csrf_config = require base_path('config/auth.php');

		// Route iteration and check pattern
		foreach ($this->routes as $route) {
			$pattern = preg_replace('/\{id\}/', '(\d+)', $route['uri']);
			// Az egyéb paraméterek továbbra is elfogadnak bármit
			$pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
			$pattern = "#^" . $pattern . "$#";

			// If pattern ok and $route method === $method
			if (preg_match($pattern, $uri, $matches) && $route['method'] === strtoupper($method)) {
				array_shift($matches);
				// If we have middleware resolve that
				if ($route['middleware']) {
					if (is_array($route['middleware'])) {
						foreach ($route['middleware'] as $middleware) {
							Middleware::resolve($middleware);
						}
					} else {
						Middleware::resolve($route['middleware']);
					}
				}

				// if $route controller is callable, call that.
				if (!is_array($route['controller']) && is_callable($route['controller'])) {
					echo call_user_func_array($route['controller'], $matches);
					Session::unflash();
					exit();
				}


				if (is_array($route['controller'])) {
					$controller = $route['controller'][0];
					$fn = $route['controller'][1];
					$method = $route['method'];


					if (in_array(strtoupper($method), $csrfProtectedMethods) && $csrf_config['csrf']['protect']) {
						//(new CSRF)->check();
						Request::unset('csrf');
						Request::unset('_method');
					}
					try {
						(new $controller)->$fn($matches);
					} finally {
						Session::unflash();
					}
					exit();
				}
			}
		}

		$this->abort(404);
	}

	protected function abort($code = 404)
	{
		http_response_code($code);

		require view_path("status/{$code}");

		die();
	}
}
