<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
use Closure;

class MiddlewareStack
{
    protected Closure $start;

    public function __construct()
    {
        $this->start = function (Request $request, Response $response) {
            return [$request, $response];
        };
    }

    public function add(MiddlewareInterface $middleware)
    {
        $next = $this->start;
        $this->start = function (Request $request, Response $response) use ($middleware, $next) {
            if (is_callable($middleware)) {
                return $middleware($next, $request, $response);
            }
        };
    }

    public function handle(Request $request, Response $response)
    {
        return call_user_func($this->start, $request, $response);
    }
}