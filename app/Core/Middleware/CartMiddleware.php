<?php

namespace App\Core\Middleware;

use App\Core\Application;

class CartMiddleware implements BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::cartIsEmpty()){
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                Application::$app->response->redirect('/');
            }
        }
    }
}