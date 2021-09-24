<?php

namespace App\Core\Request;

use InvalidArgumentException;

class RequestFactory
{
    public function create(string $requestString): IRequest
    {
        $requestObject = new $requestString();
        if (!$requestObject){
            throw new InvalidArgumentException($requestString . ' is not a valid request object');
        }

        return $requestObject;
    }
}