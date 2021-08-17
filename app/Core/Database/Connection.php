<?php


namespace App\Core\Database;


use http\Exception\InvalidArgumentException;
use PDO;
use PDOException;
use PDOStatement;

class Connection
{
    private static PDO $db;

    public static function connect()
    {
        try {
            self::$db = new PDO(getenv('DATABASE_DSN'), getenv('DATABASE_USERNAME'), getenv('DATABASE_PASSWORD'));
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return self::$db;
    }

    public static function getDb(): PDO
    {
        return self::$db;
    }

}