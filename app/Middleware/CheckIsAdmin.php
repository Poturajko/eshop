<?php

namespace App\Middleware;

use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\ProductController;
use App\Core\Application;
use App\Core\Exception\ForbiddenException;
use App\Core\Middleware\MiddlewareInterface;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Core\View\View;

class CheckIsAdmin implements MiddlewareInterface
{
    public function __invoke(callable $next, Request $request, Response $response)
    {
        try {

            if (!admin()) {
                $forbidden = [CategoryController::class, ProductController::class, OrderController::class];
                if (in_array(get_class(Application::$app->controller), $forbidden)) {
                    throw new ForbiddenException();
                }
            }

            return $next($request, $response);

        }catch (ForbiddenException $exception){
            $response->setStatusCode((int)$exception->getCode());
            exit($exception->getMessage());
        }

    }

}