<?php

namespace App\Core\Session;

interface ISession
{
    public function set(string $key, $value): void;

    public function get(string $key);

    public function delete(string $key): void;

    public function has(string $key): bool;

    public function flush(string $key);
}