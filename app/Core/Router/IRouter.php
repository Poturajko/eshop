<?php

namespace App\Core\Router;

interface IRouter
{
    public function get(string $path, array $callback): void;

    public function post(string $path, array $callback): void;

    public function resolve();
}