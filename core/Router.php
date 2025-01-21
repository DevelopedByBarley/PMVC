<?php

namespace Core;

use App\Http\Middlewares\Middleware;

class Router
{
    protected $routes = [];

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

    public function get($uri, $controller)
    {

        return  $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return  $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return  $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return   $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return   $this->add('PUT', $uri, $controller);
    }

    public function view($uri, $callback)
    {
        return $this->add('GET', $uri, $callback);
    }

    public function route($uri, $method)
    {
        $csrfProtectedMethods = ['POST', 'DELETE', 'PATCH', 'PUT'];
        $csrf_config = require base_path('config/auth.php');


        foreach ($this->routes as $route) {
            // Konvertáljuk az útvonalat regex mintává
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";


            if (preg_match($pattern, $uri, $matches) && $route['method'] === strtoupper($method)) {
                array_shift($matches); // Az első elem az egész találat
                // Middleware kezelése
                if ($route['middleware']) {
                    Middleware::resolve($route['middleware']);
                }

                if (!is_array($route['controller']) && is_callable($route['controller'])) {
                    echo call_user_func_array($route['controller'], $matches);
                    exit();
                }

                // Ha a controller tömb, hívjuk meg a metódust paraméterekkel
                if (is_array($route['controller'])) {
                    $controller = $route['controller'][0];
                    $fn = $route['controller'][1];
                    $method = $route['method'];


                    if (in_array(strtoupper($method), $csrfProtectedMethods) && $csrf_config['csrf']['protect']) {
                        (new CSRF)->check();
                    }

                    try {
                        (new $controller)->$fn($matches);
                    } finally {
                        Session::unflash();
                    }
                    exit();
                }

                // Ellenkező esetben egyszerűen betöltjük a fájlt
                return require base_path($route['controller']);
            }
        }

        // Ha nincs találat, 404-es hiba
        $this->abort();
    }


    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require view_path("status/{$code}");

        die();
    }
}
