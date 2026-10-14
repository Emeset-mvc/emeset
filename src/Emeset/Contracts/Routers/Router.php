<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Interface d'un Router.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Router que escull quin controlador s'ha d'executer
 *
 **/

namespace Emeset\Contracts\Routers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;

/**
 * Router: interficie que ha d'implementar un router.
 * @author: Dani Prados dprados@cendrassos.net
 *
 **/

interface Router
{

    public const DEFAULT_ROUTE = 0;

    public function route(string|int $id, mixed $callback, mixed $middleware = false): void;
    public function execute(Request $request, Response $response): Response;
    public function get(string|int $id, mixed $callback, mixed $middleware = false): void;
    public function post(string|int $id, mixed $callback, mixed $middleware = false): void;
    public function put(string|int $id, mixed $callback, mixed $middleware = false): void;
    public function delete(string|int $id, mixed $callback, mixed $middleware = false): void;
    public function head(string|int $id, mixed $callback, mixed $middleware = false): void;
}
