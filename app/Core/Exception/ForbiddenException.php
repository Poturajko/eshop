<?php

namespace App\Core\Exception;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = 'У вас нет прав для доступа к этой странице';
    protected $code = 403;
}