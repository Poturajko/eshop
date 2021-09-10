<?php


namespace App\Core\Database;


use InvalidArgumentException;
use PDO;
use PDOException;

class Connection
{
    private static $instance = null;

    private PDO $db;

    private function __construct()
    {
        try {
            $this->db = new PDO(getenv('DATABASE_DSN'), getenv('DATABASE_USERNAME'), getenv('DATABASE_PASSWORD'));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    private function __clone()
    {
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new Connection();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->db;
    }
}