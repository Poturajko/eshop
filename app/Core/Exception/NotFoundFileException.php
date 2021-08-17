<?php

namespace App\Core\Exception;

use Exception;

class NotFoundFileException extends Exception
{
    protected $message = 'Такого файла не существует';
}