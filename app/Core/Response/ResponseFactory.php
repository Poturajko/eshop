<?php

namespace App\Core\Response;

use InvalidArgumentException;

class ResponseFactory
{
    public function create(string $responseString):IResponse
    {
        $responseObject = new $responseString();
        if (!$responseObject){
            throw new InvalidArgumentException($responseString . ' is not a valid response object');
        }

        return $responseObject;
    }
}