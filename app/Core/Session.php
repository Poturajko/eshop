<?php

namespace App\Core;

class Session
{

    public function __construct()
    {
        session_start();
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function has(string $key): bool
    {
       return isset($_SESSION[$key]);
    }

    public function flush(string $key)
    {
        if ($this->has($key)){
            $value = $_SESSION[$key];
            $this->delete($key);

            return $value;
        }

        return null;
    }

}