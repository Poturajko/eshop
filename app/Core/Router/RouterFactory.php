<?php

namespace App\Core\Router;

use App\Core\Middleware\MiddlewareStack;
use App\Core\Request;
use App\Core\Response;
use UnexpectedValueException;

class RouterFactory
{
    private Response $response;
    private Request $request;
    private MiddlewareStack $middleware;

    public function __construct(Response $response, Request $request, MiddlewareStack $middleware)
    {
        $this->response = $response;
        $this->request = $request;
        $this->middleware = $middleware;
    }

    public function create(string $routerString): IRouter
    {
        $routerObject = new $routerString($this->request, $this->response, $this->middleware);
        if (!$routerObject instanceof IRouter){
            throw new UnexpectedValueException($routerString . ' is not a valid router object.');
        }

        return $routerObject;
    }
}