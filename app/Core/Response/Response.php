<?php
namespace App\Core\Response;

class Response implements IResponse
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