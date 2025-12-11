<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Routers\RouterParam;
use Emeset\Http\Request;
use Emeset\Http\Response;
use Emeset\Views\ViewsPHP;
use Emeset\Contracts\Container as ContainerInterface;

final class RouterParamTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_REQUEST = [];
    }

    public function testExecuteCallsDefaultRouteWhenRouteNotProvided(): void
    {
        $container = $this->createContainer();
        $router = new RouterParam($container, []);

        $router->route('', function ($req, $res) {
            $res->setBody('default');
            return $res;
        });

        $request = new Request(); // r = null → ruta '' per defecte
        $response = new Response(new ViewsPHP());

        $result = $router->execute($request, $response);

        $this->assertSame('default', $result->getBody());
    }

    public function testExecuteCallsSpecificRoute(): void
    {
        $container = $this->createContainer();
        $router = new RouterParam($container, []);

        $router->route('home', function ($req, $res) {
            $res->setBody('home');
            return $res;
        });

        $_REQUEST['r'] = 'home';

        $request = new Request();
        $response = new Response(new ViewsPHP());

        $result = $router->execute($request, $response);

        $this->assertSame('home', $result->getBody());
    }

        private function createContainer(): \Emeset\Container
    {
        return new \Emeset\Container([]);
    }
}
