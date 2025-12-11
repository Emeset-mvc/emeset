<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Caller;
use Emeset\Container as PimpleContainer;

final class CallerTest extends TestCase
{
    public function testResolveReturnsCallableWhenAlreadyCallable(): void
    {
        $container = new PimpleContainer([]);
        $caller = new Caller($container);

        $callable = function () {
            return 'ok';
        };

        $resolved = $caller->resolve($callable);

        $this->assertSame('ok', $resolved());
    }

    public function testResolveArrayClassMethod(): void
    {
        $container = new PimpleContainer([]);
        $caller = new Caller($container);

        $resolved = $caller->resolve([DummyCallableClass::class, 'hello']);

        $this->assertSame('hello', $resolved());
    }

    public function testResolveStringClassMethod(): void
    {
        $container = new PimpleContainer([]);
        $caller = new Caller($container);

        $resolved = $caller->resolve(DummyCallableClass::class . ':hello');

        $this->assertSame('hello', $resolved());
    }
}

final class DummyCallableClass
{
    public function __construct(private $container)
    {
    }

    public function hello(): string
    {
        return 'hello';
    }
}
