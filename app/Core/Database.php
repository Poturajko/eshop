<?php


namespace App;


use http\Exception\InvalidArgumentException;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private PDO $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(getenv('DATABASE_DSN'), getenv('DATABASE_USERNAME'), getenv('DATABASE_PASSWORD'));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
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

        return ($result) ?: null;
    }

    public function row(string $query, array $params = []): ?array
    {
        $result = $this->query($query, $params)->fetch();

        return ($result) ?: null;
    }

    public function column(string $query, array $params = []): ?string
    {
        $result = $this->query($query, $params)->fetchColumn();

        return ($result) ?: null;
    }

    public function lastInsertId(): ?string
    {
        $result =  $this->db->lastInsertId();

        return ($result) ?: null;
    }


}