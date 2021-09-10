<?php

namespace App\Core\Orm\DataMapper;

use App\Core\Database\Connection;
use Exception;
use LogicException;
use PDO;
use PDOStatement;
use Throwable;

class DataMapper implements IDataMapper
{
    private Connection $db;

    private PDOStatement $stmt;

    private string $className;

    public function __construct(Connection $db, string $className)
    {
        $this->db = $db;
        $this->className = $className;
    }

    private function isEmpty(string $value)
    {
        if (empty($value)) {
            throw new LogicException();
        }
    }

    public function prepare(string $query): IDataMapper
    {
        $this->isEmpty($query);
        $this->stmt = $this->db->getConnection()->prepare($query);

        return $this;
    }

    public function bind($value)
    {
        try {
            switch ($value) {
                case is_bool($value):
                    $const = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $const = PDO::PARAM_NULL;
                    break;
                case is_int($value):
                    $const = PDO::PARAM_INT;
                    break;
                default:
                    $const = PDO::PARAM_STR;
                    break;
            }
        } catch (Exception $exception) {
            throw new Exception();
        }

        return $const;
    }

    public function bindValues(array $fields): PDOStatement
    {
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $item) {
                    if (count($fields) > 1){
                        $this->stmt->bindValue(':' . $key, $item, $this->bind($item));
                    }else{
                        $this->stmt->bindValue(':' . $k, $item, $this->bind($item));
                    }
                }
            } else {
                $this->stmt->bindValue(':' . $key, $value, $this->bind($value));
            }
        }

        return $this->stmt;
    }

    protected function bindSearchValues(array $fields): PDOStatement
    {
        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }
        return $this->stmt;
    }

    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);

        return $this;
    }


    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    public function numRows(): int
    {
        if ($this->stmt) {
            return $this->stmt->rowCount();
        }
    }

    public function result(): object
    {
        return $this->stmt->fetchObject($this->className);
    }

    public function results(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_CLASS, $this->className);
    }

    public function column()
    {
        return $this->stmt->fetchColumn();
    }

    public function getLastId(): int
    {
        if ($this->db->getConnection()) {
            $lastId = $this->db->getConnection()->lastInsertId();
            if (!empty($lastId)) {
                return $lastId;
            }
        }
    }

    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || !empty($conditions)) ? array_merge($conditions, $parameters) : $parameters;
    }

    public function persist(string $query, array $parameters): bool
    {
        try {
            return $this->prepare($query)->bindParameters($parameters)->execute();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}