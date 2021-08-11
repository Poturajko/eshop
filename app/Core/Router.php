<?php

namespace App;

class Router
{
    public Response $response;
    public Request $request;
    protected array $routeMap = [];
    protected array $routeParam = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    private function rules(): array
    {
        return [
            '{code}' => '([a-zA-z]+)',
            '{id}' => '([0-9]+)',
        ];
    }

    private function setRouteParam(string $key, array $value): void
    {
        $this->routeParam[$key] = $value;
    }

    public function get(string $path, array $callback)
    {
        $this->routeMap['GET'][$path] = $callback;

        return $this;
    }

    public function post(string $path, array $callback)
    {
        $this->routeMap['POST'][$path] = $callback;

        return $this;
    }

    public function resolve()
    {
        $callback = $this->findPath();

        if (!is_null($callback)) {
            $callback[0] = new $callback[0]();
        }

        if (is_null($callback)) {
            $this->response->setStatusCode(404);
            echo Application::$app->view->renderView(Application::$app->layout, 'errors/404');
        }

        return $callback($this->request, $this->response);
    }

    private function findPath(): ?array
    {
        $requestPath = $this->request->getPath();
        $requestMethod = $this->request->method();

        foreach ($this->routeMap as $method => $routes) {
            if ($requestMethod === $method) {
                foreach ($routes as $route => $callback) {
                    if ($route === $requestPath) {
                        $this->setRouteParam($route, $callback);

                        return $callback;
                    }

                    $pattern = $this->makePattern($route);
                    if (preg_match('#^' . $pattern . '$#', $requestPath, $matches)) {
                        $this->setRouteParam(array_shift($matches), $callback);
                        $this->request->setParams($matches);

                        return $callback;
                    }
                }
            }
        }

        return null;
    }

    private function makePattern(string $path): string
    {
        $data = explode('/', $path);

        foreach ($this->rules() as $key => $rule) {
            foreach ($data as $k => $item) {
                if ($key === $item) {
                    $data[$k] = $rule;
                }
            }
        }

        return implode('/', $data);
    }

    public function getCurrentRoute(): ?string
    {
        if (isset($this->routeParam[$this->request->getPath()])) {

            return array_key_first($this->routeParam);
        }

        return null;
    }

    public function routeActive(string $actionName): bool
    {
        foreach ($this->routeParam as $values) {
            if (in_array($actionName, $values)) {

                return true;
            }
        }

        return false;
    }

}
