<?php


namespace App;


abstract class Controller
{
    protected array $handler;
    protected object $view;

    public function __construct(array $callback)
    {
        $this->handler = $callback;
        $this->view = new View($callback);
    }
}