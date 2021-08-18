<?php

namespace App\Core\Middleware;

use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\ProductController;
use App\Core\Application;
use App\Core\Exception\ForbiddenException;
use App\Core\Request;
use App\Core\Response;

class CheckIsAdmin implements MiddlewareInterface
{
    public function __invoke(callable $next, Request $request, Response $response)
    {
        if (!Application::isAdmin()) {
            if (in_array(get_class(Application::$app->controller),
                [CategoryController::class, ProductController::class, OrderController::class])) {
                $response->setStatusCode(401);
                throw new ForbiddenException();
            }
        }
        return $next($request, $response);
    }

}