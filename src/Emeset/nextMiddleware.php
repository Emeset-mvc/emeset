<?php

use Emeset\Middleware;

/**
 * @param $request
 * @param $response
 * @param $container
 * @param $next
 * @return mixed
 */
#[\Deprecated(message: 'Will be removed in version 1.0, use Emeset\Middleware::next() instead')]
function nextMiddleware(\Emeset\Http\Request $request, \Emeset\Http\Response $response, \Emeset\Container $container, mixed $next): \Emeset\Http\Response
{
    return Middleware::next($request, $response, $container, $next);
}
