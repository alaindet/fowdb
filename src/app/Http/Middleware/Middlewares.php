<?php

namespace App\Http\Middleware;

use App\Http\Middleware\ApiCheckAuthorizationMiddleware;
use App\Http\Middleware\ApiCheckCsrfTokenMiddleware;
use App\Http\Middleware\CheckAuthorizationMiddleware;
use App\Http\Middleware\CheckCsrfTokenMiddleware;
use App\Http\Request\Request;

class Middlewares
{
    private $all = [
        "auth"  => CheckAuthorizationMiddleware::class,
        "token" => CheckCsrfTokenMiddleware::class,
        "api" => ApiEnvironmentMiddleware::class,
        "api-auth" => ApiCheckAuthorizationMiddleware::class,
        "api-token" => ApiCheckCsrfTokenMiddleware::class,
    ];

    private $defaults = [
        "auth"  => CheckAuthorizationMiddleware::class,
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

            $name = &$list[$i];

            // Remove middleware, ex.: !token
            if ($name[0] === "!") {
                $_name = substr($name, 1);
                if (isset($this->list[$_name])) unset($this->list[$_name]);
            }
            
            // Add new middleware
            else {
                $this->list[$name] = $this->all[$name];
            }
        }

        return $this;
    }

    public function run(Request $request): void
    {
        $middlewares = array_values($this->list);
        for ($i = 0, $len = count($middlewares); $i < $len; $i++) {
            $middleware = &$middlewares[$i];
            (new $middleware)->run($request);
        }
    }
}
