<?php


namespace App\Core;


use App\Core\Middleware\BaseMiddleware;

class Controller
{
    public string $layout = 'master';
    public string $action = '';

    /**
     * @var \App\Core\Middleware\BaseMiddleware $middlewares
     */
    protected array $middlewares = [];

    public function render($layout, $view, $params = []):void
    {
        echo Application::$app->view->renderView($layout, $view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware):void
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares()
    {
        return $this->middlewares;
    }


}