<?php

namespace App\Core\View;

use InvalidArgumentException;

class ViewFactory
{
    public function create(string $viewString):IView
    {
        $viewObject = new $viewString();
        if (!$viewObject){
            throw new InvalidArgumentException($viewString . ' is not a valid view object');
        }

        return $viewObject;
    }
}