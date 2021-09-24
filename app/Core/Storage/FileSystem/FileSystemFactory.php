<?php

namespace App\Core\Storage\FileSystem;

use InvalidArgumentException;

class FileSystemFactory
{
    public function create(string $fileSystemString) : IFileSystem
    {
        $fileSystemObject = new $fileSystemString();
        if (!$fileSystemObject){
            throw new InvalidArgumentException($fileSystemString . ' is not a valid FileSystem object');
        }

        return $fileSystemObject;
    }
}