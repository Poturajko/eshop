<?php

namespace App\Core\Response;

interface IResponse
{
    public function setStatusCode(int $code);

    public function redirect($url);

    public function back(): void;
}