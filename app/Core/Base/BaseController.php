<?php


namespace App\Core\Base;


use App\Core\Application;

class BaseController
{
    public string $action = '';

    public function render($layout, $view, $params = []): void
    {
        echo Application::$app->view->renderView($layout, $view, $params);
    }
}