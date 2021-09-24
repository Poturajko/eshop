<?php

namespace App\Core\Request;

class Request implements IRequest
{
    private array $files = [];

    private array $body = [];

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

    public function has($key): bool
    {
        if (empty($this->body)) {
            $this->getBody();
        }
        if (is_array($key)) {
            foreach ($key as $value) {
                if (isset($this->body[$value]) && !empty($this->body[$value])) {
                    return true;
                }
            }

            return false;
        }

        return isset($this->body[$key]) && !empty($this->body[$key]);
    }

    public function file(string $key): array
    {
        $this->files = $_FILES[$key];

        return $this->files;
    }

    public function getBody(): array
    {
        if ($this->method() === 'GET') {
            foreach ($_GET as $key => $value) {
                $this->body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'POST') {
            foreach ($_POST as $key => $value) {
                $this->body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if ($_FILES) {
                foreach ($_FILES as $key => $value) {
                    if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                        $this->body[$key] = $value;
                    }
                }
            }
        }

        return $this->body;
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