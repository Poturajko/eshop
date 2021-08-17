<?php

namespace App\Core;

use App\Core\Exception\NotFoundFileException;

class Storage
{
    public static function delete(string $directory):bool
    {
        if (file_exists($directory)){
            unlink($directory);
            return true;
        }

        throw new NotFoundFileException();
    }
}