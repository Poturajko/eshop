<?php

namespace App\Core\Database;

use App\Core\Application;
use App\Core\Model;
use PDO;
use PDOStatement;

class DatabaseModel extends Model
{
    public const SHOW_BY_DEFAULT = 6;

    private array $params;
    private string $sql;

    public string $base;
    public array $where;
    public string $whereIn;
    public string $type;
    public string $limit;
    public string $offset;

    public QueryBuilder $builder;

    public function __construct()
    {
        $this->builder = new QueryBuilder();
    }

    public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->prepare($query);
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

    public function prepare(string $query)
    {
        return Application::$app->db->prepare($query);
    }

    public function lastInsertId(): ?string
    {
        return Application::$app->db->lastInsertId() ?: null;
    }

    public function count(): ?int
    {
        $this->sql = $this->builder->select('COUNT(*) as count', $this->tableName())->getSQL();

        return $this->query($this->sql)->fetchColumn() ?: null;
    }

    public function paginate(int $limit = self::SHOW_BY_DEFAULT)
    {
        $page = Application::$app->request->getBody()['page'] ?: 1;
        $offset = ($page - 1) * $limit;
        $sql = $this->builder->select($this->attributes(), $this->tableName())->limit($limit)->offset($offset)->getSQL();

        return $this->query($sql)->fetchAll(PDO::FETCH_CLASS,static::class);
    }


    public function all(): ?array
    {
        $this->sql = $this->builder->select('*', $this->tableName())->getSQL();

        return $this->query($this->sql)->fetchAll(PDO::FETCH_CLASS, static::class) ?: null;
    }

    public function get(): ?array
    {
        return $this->query($this->sql, $this->params)->fetchAll(PDO::FETCH_CLASS, static::class) ?: null;
    }

    public function whereIn(string $field, array $value): self
    {
        $this->params = $value;
        $this->sql = $this->builder->select($this->attributes(), $this->tableName())->whereIn($field, $value)->getSQL();

        return $this;
    }

    public function where(string $field, string $value, string $operator = '='): self
    {
        $this->params = [$field => $value];
        $this->sql = $this->builder
            ->select($this->attributes(), $this->tableName())
            ->where($field, $operator)
            ->getSQL();

        return $this;
    }

    public function first(): ?self
    {
        return $this->query($this->sql, $this->params)->fetchObject(static::class) ?: null;
    }

    public function delete(): bool
    {
        $params = [$this->primaryKey() => $this->id];
        $this->sql = $this->builder->delete($this->tableName())->where($this->primaryKey())->getSQL();
        $stmt = $this->query($this->sql, $params);

        return $stmt->rowCount() > 0;
    }

    public function update(array $fields = []): bool
    {
        $attr = $this->attributes();
        if (!$fields) {
            foreach ($this->attributes() as $attribute) {
                $params[$attribute] = $this->{$attribute};
            }
        } else {
            $attr = array_keys($fields);
            $params = $fields;
            $params['id'] = $this->id;
        }
        $this->sql = $this->builder->update($this->tableName(), $attr)->where($this->primaryKey())->getSQL();

        return $this->query($this->sql, $params)->rowCount() > 0;
    }

    public function create(array $fields = []): ?string
    {
        $attr = [];
        if (!$fields) {
            foreach ($this->attributes() as $key => $attribute) {
                if ($this->{$attribute}) {
                    $params[$attribute] = $this->{$attribute};
                    $attr[$key] = $attribute;
                }
            }
        } else {
            $attr = array_keys($fields);
            $params = $fields;
        }
        $this->sql = $this->builder->insert($this->tableName(), $attr)->getSQL();
        $this->query($this->sql, $params);

        return $this->lastInsertId();
    }

    public function hasMany(string $related): ?array
    {
        $instance = new $related();
        $params = [$instance->foreignKey() => $this->{$this->primaryKey()}];
        $this->sql = $this->builder->select($instance->attributes(),
            $instance->tableName())->where($instance->foreignKey())->getSQL();

        return $instance->query($this->sql, $params)->fetchAll(PDO::FETCH_CLASS, $related);
    }

    public function belongsTo(string $related): self
    {
        $instance = new $related();
        $this->params = [$instance->primaryKey() => $this->{$this->foreignKey()}];
        $this->sql = $this->builder->select($instance->attributes(),
            $instance->tableName())->where($instance->primaryKey())->getSQL();

        return $this->first();
    }

    public function belongsToMany(string $related)
    {
        $instance = new $related();
        $params = [$this->primaryKey() => $this->{$this->primaryKey()}];
        $this->sql = $this->builder->select('*', $this->pivotTable())
            ->where($this->foreignPivotKey(), '=', $this->primaryKey())
            ->getSQL();
        $pivot = $this->query($this->sql, $params)->fetchAll();
        foreach ($pivot as $item) {
            $param [] = $item[$instance->foreignPivotKey()];
        }
        $this->sql = $this->builder->select($instance->attributes(), $instance->tableName())
            ->whereIn($instance->primaryKey(), $param)->getSQL();

        return $this->query($this->sql, $param)->fetchAll(PDO::FETCH_CLASS, $related);
    }

}