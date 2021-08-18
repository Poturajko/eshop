<?php

namespace App\Core;

use App\Controllers\MainController;
use App\Core\Exception\NotFoundException;
use App\Core\Middleware\CartIsEmpty;
use App\Core\Middleware\MiddlewareStack;

class Router
{
    public Response $response;
    public Request $request;
    public MiddlewareStack $middleware;

    public array $routeMap = [];

    public function __construct(Request $request, Response $response, MiddlewareStack $middleware)
    {
        $this->request = $request;
        $this->response = $response;
        $this->middleware = $middleware;
    }

    public function get(string $path, array $callback): void
    {
        $this->routeMap['GET'][$path] = $callback;
    }

    public function post(string $path, array $callback): void
    {
        $this->routeMap['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $requestPath = $this->request->getPath();
        $requestMethod = $this->request->method();
        $params = [];

        foreach ($this->routeMap as $method => $paths) {
            foreach ($paths as $path => $handler) {
                $internalRoute = preg_replace('/{[^}]+}/', '(.+)', $path);

                if ($requestMethod === $method && $requestPath === $path) {
                    $callback = $handler;

                    break;
                }

                if (preg_match("%^{$internalRoute}$%", $requestPath, $matches) === 1) {
                    if ($requestMethod === $method && $requestPath === $matches[0]) {
                        $params = explode('/', $matches[1]);
                        $callback = $handler;
                        break;
                    }
                }
            }
        }

        if ($callback) {
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $this->middleware->handle($this->request, $this->response);
            $callback[0] = $controller;
        }

        if (!$callback) {
            throw new NotFoundException();
        }

        return call_user_func_array([$callback[0], $callback[1]],
            array_merge_recursive([$this->request, $this->response], $params));
    }

}
