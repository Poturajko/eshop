<?php

use App\Core\Exception\FileUploadException;
use App\Core\Response\Response;
use App\Core\Storage\File;

function url(?string $path): string
{
    try {
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . ($_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '');

        return $url . DS . (new File())->url($path);
    } catch (FileUploadException $exception) {
        return $url . DS . 'storage/default/default.png';
    }
}

function redirect(string $url): void
{
    (new Response())->redirect($url);
}

function back(): void
{
    (new Response())->back();
}