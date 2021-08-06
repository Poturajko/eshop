<?php


namespace App;


abstract class Model
{
    protected object $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}