<?php

namespace App\Http\Response;

use App\Exceptions\RouterException;
use App\Http\Request\Request;

use Symfony\Component\Routing\Matcher\UrlMatcher as SymfonyUrlMatcher;
use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;
use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * This class maps a request to its controller and action.
 * It does not instantiate or execute!
 * 
 * Doc here
 * https://symfony.com/doc/current/components/routing.html
 */
class Router
{
    private $routes;
    private $request;
    private $uri;

    public function setRoutes(array $routes): Router
    {
        $this->routes = new SymfonyRouteCollection();

        foreach ($routes as $access => $routesGroup) {
            foreach ($routesGroup as $route) {
                [$name, $thisRoute] = $this->setRoute($access, $route);
                $this->routes->add($name, $thisRoute);
            }
        }

        return $this;
    }

    public function setRequest(Request $request): Router
    {
        $this->request = new SymfonyRequestContext(
            $baseUrl = $request->baseUrl(),
            $method = $request->method(),
            $host = $request->host(),
            $scheme = $request->scheme(),
            $httpPort = $request->httpPort(),
            $httpsPort = $request->httpsPort(),
            $path = $request->path(),
            $queryString = $request->queryString()
        );

        $this->uri = $request->path();

        return $this;
    }

    private function setRoute(string $access, array $route): array
    {
        $httpMethod = $route[0];
        $uri = '/'.$route[1];
        $controller = $route[2];
        $method = $route[3];
        $parameters = $route[4] ?? [];
        $middleware = $route[5] ?? [];

        
        $this->uri = $uri;

        // Create the new route
        $route = new SymfonyRoute(

            // URI
            $uri,

            // Defaults (Will be passed to the matcher)
            [
                '_controller' => $controller,
                '_method' => $method,
                '_access' => $access,
                '_middleware' => $middleware
            ],

            // Regex parameters
            $parameters,

            [], // Options?
            null, // Host?

            // Schemes
            [ 'http', 'https' ],

            // Method
            [ $httpMethod ]
        );

        // Build a name for this route
        $name = $httpMethod.$uri; // Ex.: GET/clusters/update/{d}

        return [$name, $route];
    }

    /**
     * Matches this URI to its route
     * 
     * Ex.: 
     * 
     * # Uri
     * /foo/{par1}/bar/{par2}
     *
     * # Corresponding route
     * ```
     * [
     *     ...ROUTE_DEFAULTS,
     *     '_route' => ROUTE_NAME,
     *     'par1' => PAR_1_VALUE,
     *     'par2' => PAR_2_VALUE,
     * ]
     * ```
     *
     * Where
     * - `ROUTE_DEFAULTS` like controller and action
     * - `PAR_*_VALUE` are values of the URI parameters
     * - `ROUTE_NAME` is whatever route name you passed to this route
     *
     * @return array See above
     */
    public function match()
    {
        try {   
            $urlMatcher = new SymfonyUrlMatcher($this->routes, $this->request);
            return $urlMatcher->match($this->uri);
        }
        catch (ResourceNotFoundException $exception) {
            throw new RouterException(
                "There's no <strong>{$this->uri}</strong> route on FoWDB."
            );
        }
    }
}
