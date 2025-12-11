<?php

use PHPUnit\Framework\TestCase;
use Emeset\Middleware;
use Emeset\Contracts\Container as ContainerInterface;
use Emeset\Contracts\Http\Request as RequestInterface;
use Emeset\Contracts\Http\Response as ResponseInterface;

final class MiddlewareTest extends TestCase
{
    public function testNextExecutesSingleCallable(): void
    {
        $request = $this->createStub(RequestInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $container = $this->createContainer();

        $called = false;

        $callable = function ($req, $res, $c) use (&$called) {
            $called = true;
            return $res;
        };

        $result = Middleware::next($request, $response, $container, $callable);

        $this->assertTrue($called);
        $this->assertSame($response, $result);
    }

    public function testNextExecutesArrayOfMiddlewaresAndController(): void
    {
        $request = $this->createStub(RequestInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $container = $this->createContainer();

        $log = [];

        $mw1 = function ($req, $res, $c, $next) use (&$log) {
            $log[] = 'mw1-before';
            $res = \Emeset\Middleware::next($req, $res, $c, $next);
            $log[] = 'mw1-after';
            return $res;
        };

        $controller = function ($req, $res, $c) use (&$log) {
            $log[] = 'controller';
            return $res;
        };

        $result = Middleware::next($request, $response, $container, [$mw1, $controller]);

        $this->assertSame($response, $result);
        $this->assertSame(
            ['mw1-before', 'controller', 'mw1-after'],
            $log
        );
    }

    private function createContainer(): \Emeset\Container
    {
        return new \Emeset\Container([]);
    }
}
