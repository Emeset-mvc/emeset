<?php

namespace Emeset\Contracts;
use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

interface Middleware
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
    public static function next(Request $request, Response $response, Container $container, mixed $next): Response;
    
}
