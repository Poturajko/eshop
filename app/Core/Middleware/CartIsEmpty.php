<?php

namespace App\Core\Middleware;

use App\Core\Application;
use App\Core\Request;
use App\Core\Response;

class CartIsEmpty implements MiddlewareInterface
{
    public function __invoke(callable $next, Request $request, Response $response)
    {
        if (Application::cartIsEmpty()) {
            if (in_array(Application::$app->controller->action, ['cart', 'checkout'])) {
                $response->redirect('/');
            }
        }

        return $next($request, $response);
    }
}