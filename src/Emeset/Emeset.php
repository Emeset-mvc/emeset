<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Classe que gestiona l'aplicació.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Emeset,  permet instanciar un objecte de l'aplicació definir el router que s'utilitzarà,
 * les routes, la configuració i finalment execute l'aplicació.
 *
 **/

namespace Emeset;
require_once __DIR__ . '/nextMiddleware.php';
/**
 * Emeset: objecte que encapsula l'aplicació web.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Permet definir quin router utilitzem les routes, la configuració i finalment
 * executer l'aplicació.
 *
 **/
class Emeset
{

    public array $config = [];
    public mixed $constructor;
    public \Emeset\Contracts\Http\Response $response;
    public \Emeset\Contracts\Http\Request $request;

    public FrontController $frontController;

    public array $middleware = [];

    public function __construct(public \Emeset\Contracts\Container $contenidor)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->response = $this->contenidor->get("response");
        $this->request = $this->contenidor->get("request");
        $this->frontController = $this->contenidor->get("FrontController");
    }

    public function middleware(callable|array|string $callback): void
    {
        $this->middleware[] = $callback;
    }

    public function route(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->frontController->route($id, $callback, $middleware);
    }

    public function get(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->frontController->get($id, $callback, $middleware);
    }
    
    public function post(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->frontController->post($id, $callback, $middleware);
    }
    public function put(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->frontController->put($id, $callback, $middleware);
    }
    public function delete(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->frontController->delete($id, $callback, $middleware);
    }
    public function head(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->frontController->head($id, $callback, $middleware);
    }

    public function handle(): \Emeset\Contracts\Http\Response
    {
        $this->middleware[] = $this->frontController->execute(...);
        $response = Middleware::next(
            $this->request,
            $this->response,
            $this->contenidor,
            $this->middleware
        );

        return $response;
    }
    public function execute(): void
    {
        $response = $this->handle();
        $response->response();
    }
}
