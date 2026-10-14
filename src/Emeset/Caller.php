<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */

namespace Emeset;

/**
 * This class resolves a string of the format 'class:method' into a closure
 * that can be dispatched.
 */
class Caller
{
    public const CALLABLE_PATTERN = '!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!';

    /**
     * @param \Pimple\Container $container
     */
    public function __construct(private readonly \Pimple\Container $container)
    {
    }

    /**
     * Resolve toResolve into a closure so that the router can dispatch.
     *
     * If toResolve is of the format 'class:method', then try to extract 'class'
     * from the container otherwise instantiate it and then dispatch 'method'.
     *
     * @param callable|string $toResolve
     *
     * @return callable
     *
     * @throws \RuntimeException If the callable does not exist
     * @throws \RuntimeException If the callable is not resolvable
     */
    public function resolve(callable|array|string $toResolve): callable
    {
        if (is_callable($toResolve) && !is_array($toResolve)) {
            return $toResolve;
        }

        $resolved = $toResolve;

        if (is_array($toResolve)) {
            $resolved = $this->resolveCallable($toResolve[0], $toResolve[1]);
        }

        if (is_string($toResolve)) {
            [$class, $method] = $this->parseCallable($toResolve);
            $resolved = $this->resolveCallable($class, $method);
        }

        $this->assertCallable($resolved);
        return $resolved;
    }

    /**
     * Extract class and method from toResolve
     *
     * @param string $toResolve
     *
     * @return array
     */
    protected function parseCallable(string $toResolve): array
    {
        if (preg_match(self::CALLABLE_PATTERN, $toResolve, $matches)) {
            return [$matches[1], $matches[2]];
        }

        return [$toResolve, '__invoke'];
    }

    /**
     * Check if string is something in the DIC
     * that's callable or is a class name which has an __invoke() method.
     *
     * @param string $class
     * @param string $method
     *
     * @return callable
     *
     * @throws \RuntimeException if the callable does not exist
     */
    protected function resolveCallable(string $class, string $method): callable
    {
        $class = ltrim($class, '\\');
        if ($this->container->has("\\" . $class)) {
            return [$this->container->get("\\" . $class), $method];
        }

        if (!class_exists($class)) {
            throw new \Exception(sprintf('Callable %s does not exist', $class));
        }

        return [new $class($this->container), $method];
    }

    /**
     * @param callable $callable
     *
     * @throws \RuntimeException if the callable is not resolvable
     */
    protected function assertCallable(mixed $callable): void
    {
        if (!is_callable($callable)) {
            throw new \Exception(sprintf(
                '%s is not resolvable',
                is_array($callable) || is_object($callable) ? json_encode($callable) : $callable
            ));
        }
    }
}
