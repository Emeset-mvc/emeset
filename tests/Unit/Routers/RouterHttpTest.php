<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Routers\RouterHttp;
use Emeset\Http\Request;
use Emeset\Http\Response;
use Emeset\Views\ViewsPHP;
use Emeset\Contracts\Container as ContainerInterface;

final class RouterHttpTest extends TestCase
{
    public function testExecuteCallsRegisteredRoute(): void
    {
        $container = $this->createContainer();
        $router = new RouterHttp($container, []);

        // Ruta per defecte per a 404 / mètodes no permesos
        $router->get(0, function ($req, $res) {
            $res->setBody('default');
            return $res;
        });

        $router->get('/hello', function ($req, $res) {
            $res->setBody('hello');
            return $res;
        });

        $request = Request::fake(
            [],
            [],
            [],
            [],
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/hello',
            ]
        );
        $response = new Response(new ViewsPHP());

        $result = $router->execute($request, $response);

        $this->assertSame('hello', $result->getBody());
    }

    public function testExecuteFallsBackToDefaultRouteOnNotFound(): void
    {
        $container = $this->createContainer();
        $router = new RouterHttp($container, []);

        $router->get(0, function ($req, $res) {
            $res->setBody('default');
            return $res;
        });

        $request = Request::fake(
            [],
            [],
            [],
            [],
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/unknown',
            ]
        );
        $response = new Response(new ViewsPHP());

        $result = $router->execute($request, $response);

        $this->assertSame('default', $result->getBody());
    }

    private function createContainer(): \Emeset\Container
    {
        return new \Emeset\Container([]);
    }
}
