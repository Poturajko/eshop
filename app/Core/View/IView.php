<?php

namespace App\Core\View;

interface IView
{
    public function renderView($layoutName, $view, array $params = []);
}