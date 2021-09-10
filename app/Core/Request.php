<?php

namespace App\Core;

class Request
{
    private array $files;

    public function getPath(): string
    {
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return ($requestPath !== '/') ? rtrim($requestPath, '/') : $requestPath;
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function has($search): bool
    {
        if (is_string($search)) {
            return array_key_exists($search, $this->getBody()) && !empty($this->getBody()[$search]);
        }
        if (is_array($search)) {
            foreach ($search as $item) {
                return array_key_exists($item, $this->getBody()) && !empty($this->getBody()[$item]);
            }
        }
    }

    private function hashName()
    {
        $arrayType = explode('/', $this->files['type']);

        return time() . md5($this->files['name']) . '.' . end($arrayType);
    }

    public function getClientOriginalName(): string
    {
        return $this->files['name'];
    }

    public function file(string $key): Request
    {
        $this->files = $_FILES[$key];

        return $this;
    }

    public function store(string $path, bool $originalName = false): ?string
    {
        $pathTo = STORAGE_DIR . '/' . $path . '/';
        if (!@mkdir($pathTo) && !is_dir($pathTo)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathTo));
        }
        $pathTo .= !$originalName ? $this->hashName() : $this->getClientOriginalName();

        return move_uploaded_file($this->files['tmp_name'], $pathTo) ? $pathTo : null;
    }

    public function getBody(): array
    {
        $body = [];
        if ($this->method() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if ($_FILES) {
                foreach ($_FILES as $key => $value) {
                    if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                        $body[$key] = $value;
                    }
                }
            }
        }

        return $body;
    }

    protected function clearStr(string $str): string
    {
        return trim(strip_tags($str));
    }

    protected function clearNum(string $num): int
    {
        return (!empty($num) && preg_match('/\d/', $num)) ? preg_replace('/[^\d.]/', '', $num) * 1 : 0;
    }
}