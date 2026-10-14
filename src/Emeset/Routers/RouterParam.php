<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Router a partir d'un parametre d'entrada.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Router que escull quin controlador s'ha d'executer
 *
 **/

namespace Emeset\Routers;

use Emeset\Middleware;
use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Exception;

/**
 * Router: objecte que enroute a la petició al controlador adequat.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Permet definir les routes dels controladors
 *
 **/
class RouterParam implements \Emeset\Contracts\Routers\Router
{
    /** @var array<int|string, array{mixed, mixed}> */
    public array $routes = [];
    public \Emeset\Caller $caller;


    public function __construct(public \Pimple\Container $container, public array $config)
    {
        $this->caller = $this->container["caller"];
    }

    /**
     * Defineix el controlador i el middleware d'una route.
     *
     * @param string $id
     * @param callable $callback
     * @param callable $middleware
     * @return void
     */
    public function route(string|int $id, mixed $callback, mixed $middleware = false): void
    {
        $this->routes[$id] = [$callback, $middleware];
    }

    /**
     * execute el controlador vinculat a la route definida
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function execute(Request $request, Response $response): Response
    {
        $route = $request->get("INPUT_REQUEST", "r");

        if (is_null($route)) {
            $route = "";
        }

        if (isset($this->routes[$route])) {
            $controller = $this->routes[$route];
        } elseif (isset($this->routes[0])) {
            $controller = $this->routes[0];
        } else {
            throw (new Exception("Ruta no definida!"));
        }


        $action = [];
        $call = $this->caller->resolve($controller[0]);
        if ($controller[1]) {
            // si té middleware definit l'executem 
            if (is_array($controller[1])) {
                array_push($action, ...$controller[1]);
            } else {
                array_push($action, $controller[1]);
            }
            array_push($action, $call);
        } else {
            // si no té middleware
            //$response = $controller[0]($request, $response, $this->config);
            $action[] = $call;
        }
        $response = Middleware::next($request, $response, $this->container, $action);

        return $response;
    }


    public function get(string|int $id, mixed $callback, mixed $middleware = false): void
    {
        $this->route($id, $callback, $middleware);
    }
    public function post(string|int $id, mixed $callback, mixed $middleware = false): void
    {
        $this->route($id, $callback, $middleware);
    }

    public function put(string|int $id, mixed $callback, mixed $middleware = false): void
    {
        $this->route($id, $callback, $middleware);
    }
    public function delete(string|int $id, mixed $callback, mixed $middleware = false): void
    {
        $this->route($id, $callback, $middleware);
    }

    public function head(string|int $id, mixed $callback, mixed $middleware = false): void
    {
        $this->route($id, $callback, $middleware);
    }
}
