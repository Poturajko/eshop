<?php

namespace App;

class Router
{
    private array $handlers;
    private $notFoundHandler;
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private array $rules = ['{code}' => '([a-zA-z]+)', '{id}' => '([0-9]+)',];

    public function get(string $path, $handler)
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);

        return $this;
    }

    public function post(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers [$path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler,
            'params' => [],
        ];
    }

    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function run(): void
    {
        $data = $this->findPath();


        if (!is_null($data)) {
            $className = reset($data['handler']);
            $handler = new $className($data['handler']);

            $method = end($data['handler']);
            $callback = [$handler, $method];
        }

        if (!$callback) {
            header('HTTP/1.0 404 NOT FOUND');
            if (!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        }

        call_user_func_array($callback, $data['params']);
    }

    private function findPath(): ?array
    {
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $requestPath = ($requestPath !== '/') ? rtrim($requestPath, '/') : $requestPath;

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->handlers as $path => $handler) {
            if ($handler['path'] === $requestPath && $method === $handler['method']) {

                return $handler;
            }

            $pattern = $this->makePattern($path);
            if (preg_match('#^' . $pattern . '$#', $requestPath, $matches) && $method === $handler['method']) {
                $handler['path'] = array_shift($matches);
                $handler['params'] = $matches;

                return $handler;
            }
        }

        return null;
    }

    private function makePattern(string $path): string
    {
        $data = explode('/', $path);

        foreach ($this->rules as $key => $rule) {
            foreach ($data as $k => $item) {
                if ($key == $item) {
                    $data[$k] = $rule;
                }
            }
        }

        return implode('/', $data);
    }
}
