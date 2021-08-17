<?php

namespace App\Core\Middleware;

use App\Core\Application;
use App\Core\Exception\ForbiddenException;

class CheckIsAdminMiddleware implements BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (!Application::isAdmin()){
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                throw new ForbiddenException();
            }
        }
    }
}