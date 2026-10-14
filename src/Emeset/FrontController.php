<?php


namespace Emeset;

class FrontController {

    public \Emeset\Contracts\Routers\Router $router;

    public function __construct(public \Emeset\Contracts\Container $container)
    {
        $this->router = $this->container->get("router");
    }

    public function route(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->router->route($id, $callback, $middleware);
    }

    public function get(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->router->get($id, $callback, $middleware);
    }
    
    public function post(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->router->post($id, $callback, $middleware);
    }
    public function put(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->router->put($id, $callback, $middleware);
    }
    public function delete(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->router->delete($id, $callback, $middleware);
    }
    public function head(string|int $id, callable|array|string $callback, callable|array|string|false $middleware = false): void
    {
        $this->router->head($id, $callback, $middleware);
    }

    public function execute(\Emeset\Contracts\Http\Request $request, \Emeset\Contracts\Http\Response $response, \Emeset\Contracts\Container $container): \Emeset\Contracts\Http\Response
    {
        $response = $this->router->execute($request, $response);
        return $response;
    }


}
