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

    public $contenidor;
    public $config = [];
    public $constructor = null;
    public $response;
    public $request;

    public $frontController;

    public $middleware = [];

    public function __construct(\Emeset\Contracts\Container $contenidor)
    {
        $this->contenidor = $contenidor;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->response = $contenidor["response"];
        $this->request = $contenidor["request"];
        $this->frontController = $contenidor["FrontController"];
    }

    public function middleware($callback)
    {
        $this->middleware[] = $callback;
    }

    public function route($id, $callback, $middleware = false)
    {
        $this->frontController->route($id, $callback, $middleware);
    }

    public function get($id, $callback, $middleware = false)
    {
        $this->frontController->get($id, $callback, $middleware);
    }
    
    public function post($id, $callback, $middleware = false)
    {
        $this->frontController->post($id, $callback, $middleware);
    }
    public function put($id, $callback, $middleware = false)
    {
        $this->frontController->put($id, $callback, $middleware);
    }
    public function delete($id, $callback, $middleware = false)
    {
        $this->frontController->delete($id, $callback, $middleware);
    }
    public function head($id, $callback, $middleware = false)
    {
        $this->frontController->head($id, $callback, $middleware);
    }

    public function handle(): \Emeset\Contracts\Http\Response
    {
        $this->middleware[] = [$this->frontController, "execute"];
        $response = Middleware::next(
            $this->request,
            $this->response,
            $this->contenidor,
            $this->middleware
        );

        return $response;
    }
    public function execute()
    {
        $response = $this->handle();
        $response->response();
    }
}
