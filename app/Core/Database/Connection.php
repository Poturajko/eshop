<?php


namespace App\Core\Database;


use http\Exception\InvalidArgumentException;
use PDO;
use PDOException;

class Database
{
    private PDO $db;

    public function __construct(string $dsn, string $userName = null, string $password = null)
    {
        try {
            $this->db = new PDO(getenv('DATABASE_DSN'), getenv('DATABASE_USERNAME'), getenv('DATABASE_PASSWORD'));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    public function getDb(): PDO
    {
        return $this->db;
    }


}