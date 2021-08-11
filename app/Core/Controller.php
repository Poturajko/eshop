<?php


namespace App;


class Controller
{
    public string $layout = 'master';

    public function render($layout, $view, $params = [])
    {
        echo Application::$app->view->renderView($layout, $view, $params);
    }
}