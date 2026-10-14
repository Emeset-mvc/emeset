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
    public $routes = [];
    public $caller;


    public function __construct(public $container, public $config)
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
    public function route($id, $callback, $middleware = false)
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
    public function execute(Request $request, Response $response)
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


    public function get($id, $callback, $middleware = false)
    {
        $this->route($id, $callback, $middleware);
    }
    public function post($id, $callback, $middleware = false)
    {
        $this->route($id, $callback, $middleware);
    }

    public function put($id, $callback, $middleware = false)
    {
        $this->route($id, $callback, $middleware);
    }
    public function delete($id, $callback, $middleware = false)
    {
        $this->route($id, $callback, $middleware);
    }

    public function head($id, $callback, $middleware = false)
    {
        $this->route($id, $callback, $middleware);
    }
}
