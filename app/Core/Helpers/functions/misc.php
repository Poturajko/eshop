<?php

use App\Core\Application;
use App\Core\Session\ISession;
use App\Core\Session\Session;
use App\Core\Session\SessionFactory;

function session(string $key = null, $value = null): ISession
{
    $session = (new SessionFactory())
        ->create(Session::class);
    if (!is_null($key) && !is_null($value)) {
        $session->set($key, $value);
    }

    return $session;
}

function auth(string $key = 'user')
{
    return session()->get($key);
}

function admin()
{
    return Application::$app->user->is_admin;
}

function guest()
{
    return !Application::$app->user;
}

function _message(string $subject, $params): string
{
    if (is_array($params)) {
        return preg_replace_callback('/{%\d+}/', function () use (&$params) {
            return array_shift($params);
        }, $subject);
    }

    return preg_replace('/{%\d+}/', $params, $subject);
}
