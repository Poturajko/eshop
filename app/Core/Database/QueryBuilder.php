<?php

namespace App\Core\Database;

use Exception;

class QueryBuilder
{
    protected DatabaseModel $query;

    protected function reset(): void
    {
        $this->query = new DatabaseModel();
    }

    public function update(string $tableName, $fields)
    {
        $this->reset();
        if (is_array($fields)){
            $fields = implode(', ', array_map(fn($attr) => "$attr = :$attr", $fields));
        }
        $this->query->base = 'UPDATE ' . $tableName . ' SET ' . $fields;
        $this->query->type[] = 'update';

        return $this;
    }

    public function insert(string $tableName, $fields)
    {
        $this->reset();
        if (is_array($fields)){
            $fields = implode(', ', array_map(fn($attr) => "$attr = :$attr", $fields));
        }
        $this->query->base = 'INSERT INTO ' . $tableName . ' SET ' . $fields;
        $this->query->type[] = 'insert';

        return $this;
    }

    public function delete(string $tableName): self
    {
        $this->reset();
        $this->query->base = 'DELETE FROM ' . $tableName;
        $this->query->type[] = 'delete';

        return $this;
    }

    public function select($attributes, string $tableName): self
    {
        $this->reset();
        if (is_array($attributes)) {
            $attributes = implode(', ', $attributes);
        }
        $this->query->base = 'SELECT ' . $attributes . ' FROM ' . $tableName;
        $this->query->type[] = 'select';

        return $this;
    }

    public function where(string $key, string $operator = '=', string $value = ''): self
    {
        if ($this->checkType()) {
            throw new Exception("WHERE can only be added to SELECT, UPDATE OR DELETE");
        }
        if ($value){
            $this->query->where [] = "$key $operator :$value";
        }else{
            $this->query->where [] = "$key $operator :$key";
        }

        return $this;
    }

    public function whereIn(string $key, array $params): self
    {
        if ($this->checkType()) {
            throw new Exception("WHERE IN can only be added to SELECT, UPDATE OR DELETE");
        }
        $inKeysString = implode(', ', array_map(fn($attr) => ":$attr", array_keys($params)));
        $this->query->whereIn = " WHERE $key IN ($inKeysString)";

        return $this;
    }

    public function getSQL(): string
    {
        $query = $this->query;
        $sql = $query->base;
        if (!empty($query->where)) {
            $sql .= " WHERE " . implode(' AND ', $query->where);
        }
        if (isset($query->whereIn)) {
            $sql .= $query->whereIn;
        }
        $sql .= ";";
        return $sql;
    }


    private function checkType(): bool
    {
        return in_array($this->query->type, ['select', 'update', 'delete']);
    }

}