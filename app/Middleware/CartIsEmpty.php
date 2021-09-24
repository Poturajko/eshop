<?php

namespace App\Middleware;

use App\Core\Application;
use App\Core\Middleware\MiddlewareInterface;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Models\Cart;

class CartIsEmpty implements MiddlewareInterface
{
    public function __invoke(callable $next, Request $request, Response $response)
    {
        if (Cart::cartIsEmpty()) {
            if (in_array(Application::$app->controller->action, ['cart', 'checkout'])) {
                session('warning','Корзина пуста');
                $response->redirect('/');
            }
        }

        return $next($request, $response);
    }
}