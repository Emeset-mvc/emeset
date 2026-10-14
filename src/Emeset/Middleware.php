<?php

namespace Emeset;
use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;
use Emeset\Contracts\Middleware as MiddlewareInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * Call the next middleware or page controller passed through the
     *
     * @param $request
     * @param $response
     * @param $container
     * @param $next
     * @return mixed
     */
    public static function next(Request $request, Response $response, Container $container, mixed $next): Response
    {
        if (is_array($next)) {
            if (count($next) > 1) {
                $call = array_shift($next);
                $call = $container["caller"]->resolve($call);
                $response = $call($request, $response, $container, $next);
            } else {
                $response = call_user_func($next[0], $request, $response, $container);
            }
        } else {
            $response = call_user_func($next, $request, $response, $container);
        }

        return $response;
    }
}
