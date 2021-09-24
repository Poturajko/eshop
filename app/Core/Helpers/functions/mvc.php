<?php

use App\Core\Application;

function routeActive(string $actionName): bool
{
    foreach (Application::$app->controller as $item) {
        if ($actionName === $item) {
            return true;
        }
    }

    return false;
}