<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;

class Middlewares
{
    private $all = [
        'auth'  => \App\Http\Middleware\CheckAuthorizationMiddleware::class,
        'token' => \App\Http\Middleware\CheckCsrfTokenMiddleware::class,
    ];

    private $defaults = [
        'auth'  => \App\Http\Middleware\CheckAuthorizationMiddleware::class,
        'token' => \App\Http\Middleware\CheckCsrfTokenMiddleware::class,
    ];

    private $list = [];

    /**
     * Sets the list of middleware to execute.
     * You can add or remove middleware
     * 
     * Ex.: [!token, captcha] => Remove csrf token check, add captcha check
     *
     * @param array $data
     * @return Middlewares
     */
    public function list(array $list = []): Middlewares
    {
        $this->list = $this->defaults;

        for ($i = 0, $len = count($list); $i < $len; $i++) {

            $name =& $list[$i];

            // Negate a default middleware, ex.: !token
            if ($name[0] === '!') {
                $name = substr($name, 1);
                if (isset($this->list[$name])) unset($this->list[$name]);
            }

            // Add new middleware
            if (!isset($this->list[$name])) {
                $this->list[$name] = $this->all[$name];
            }
        }

        return $this;
    }

    public function run(Request $request): void
    {
        $middlewares = array_values($this->list);
        for ($i = 0, $len = count($middlewares); $i < $len; $i++) {
            $middleware =& $middlewares[$i];
            (new $middleware)->run($request);
        }
    }
}
