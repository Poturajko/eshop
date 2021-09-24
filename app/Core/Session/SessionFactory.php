<?php

namespace App\Core\Session;

use InvalidArgumentException;

class SessionFactory
{
    public function create(string $sessionString): ISession
    {
        $sessionObject = new $sessionString();
        if (!$sessionObject) {
            throw new InvalidArgumentException($sessionString . ' is not a valid session object');
        }

        return $sessionObject;
    }
}