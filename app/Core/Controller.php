<?php


namespace App\Core;


class Controller
{
    public string $layout = 'master';
    public string $action = '';

    public function render($layout, $view, $params = []):void
    {
        echo Application::$app->view->renderView($layout, $view, $params);
    }

}