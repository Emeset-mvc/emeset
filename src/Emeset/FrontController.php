<?php


namespace Emeset;

class FrontController {

    public $router;

    public function __construct(public $container)
    {
        $this->router = $this->container["router"];
    }

    public function route($id, $callback, $middleware = false)
    {
        $this->router->route($id, $callback, $middleware);
    }

    public function get($id, $callback, $middleware = false)
    {
        $this->router->get($id, $callback, $middleware);
    }
    
    public function post($id, $callback, $middleware = false)
    {
        $this->router->post($id, $callback, $middleware);
    }
    public function put($id, $callback, $middleware = false)
    {
        $this->router->put($id, $callback, $middleware);
    }
    public function delete($id, $callback, $middleware = false)
    {
        $this->router->delete($id, $callback, $middleware);
    }
    public function head($id, $callback, $middleware = false)
    {
        $this->router->head($id, $callback, $middleware);
    }

    public function execute($request, $response, $container)
    {
        $response = $this->router->execute($request, $response);
        return $response;
    }


}