<?php

namespace App\Core\Router;

use App\Core\Application;
use App\Core\Exception\NotFoundException;
use App\Core\Middleware\MiddlewareStack;
use App\Core\Request;
use App\Core\Response;
use ReflectionMethod;

class Router implements IRouter
{
    private Response $response;
    private Request $request;
    private MiddlewareStack $middleware;

    private array $routeMap = [];

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
                    $internalPath = array_shift($matches);
                    if ($requestMethod === $method && $requestPath === $internalPath) {
                        $params = $matches;
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

        $args = $this->getParameters($controller, $controller->action, $params);

        return call_user_func_array([$callback[0], $callback[1]], $args);
    }

    private function getParameters(object $controller, string $action, array $params = []): array
    {
        $rm = new ReflectionMethod($controller, $action);
        $parameters = $rm->getParameters();
        if (isset($parameters) && !empty($parameters)) {
            $arrayParams = [];
            foreach ($parameters as $args) {
                $reflectionType = $args->getType();
                if ($reflectionType && class_exists($reflectionType->getName())) {
                    $classString = $reflectionType->getName();
                    $arrayParams [] = new $classString();
                }
            }

            return array_merge($arrayParams, $params);
        }

        return [];
    }

}
