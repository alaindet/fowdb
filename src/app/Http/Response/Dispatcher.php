<?php

namespace App\Http\Response;

use App\Http\Request\Request;
use App\Http\Middleware\Middlewares;

class Dispatcher
{
    private $controller;
    private $method;
    private $middleware;
    private $request;
    private $routeParameters = [];
    private $removables = [
        '_controller',
        '_method',
        '_access',
        '_route',
        '_middleware'
    ];

    public function setMatchedRoute(array $route = null): Dispatcher
    {
        if (isset($route)) {

            // Mandatory props
            $this->controller = $route['_controller'];
            $this->method = $route['_method'];
            $this->middleware = $route['_middleware'];

            // Optional props
            foreach ($this->removables as &$rm) unset($route[$rm]);
            $this->routeParameters = $route;
        }

        return $this;
    }

    public function setRequest(Request $request): Dispatcher
    {
        $this->request = $request;

        return $this;
    }

    public function runMiddleware(): Dispatcher
    {
        (new Middlewares)
            ->list($this->middleware)
            ->run($this->request);

        return $this;
    }

    public function dispatch(): string
    {
        // Fully-qualified class name
        $fqcn = "\\App\\Http\\Controllers\\{$this->controller}";

        $controller = new $fqcn();
        $method =& $this->method;
        $input = $this->request->input();

        if (!empty($this->routeParameters)) {
            $parameters = array_values($this->routeParameters);
            return $controller->$method($input, ...$parameters);
        }

        return $controller->$method($input);
    }
}
