<?php

namespace App\Core\Request;


interface IRequest
{
    public function method(): string;

    public function isGet(): bool;

    public function isPost(): bool;

    public function has($search): bool;

    public function getBody(): array;
}