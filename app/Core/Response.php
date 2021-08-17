<?php
namespace App\Core;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    public function back(): void
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}