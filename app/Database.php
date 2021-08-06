<?php


namespace App;


use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new PDO("mysql:host=127.0.0.1;dbname=laravel", 'root', 'root');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if (is_int($val)) {
                    $const = PDO::PARAM_INT;
                } else {
                    $const = PDO::PARAM_STR;
                }
                $stmt->bindValue(':' . $key, $val, $const);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function rows(string $query, array $params = []): ?array
    {
        $result = $this->query($query, $params)->fetchAll();
        if (!$result) {
            return null;
        }
        return $result;
    }

    public function row(string $query, array $params = []): ?array
    {
        $result = $this->query($query, $params)->fetch();
        if (!$result) {
            return null;
        }
        return $result;
    }

    public function column(string $query, array $params = [])
    {
        $result = $this->query($query, $params)->fetchColumn();
        if (!$result) {
            return null;
        }
        return $result;
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}