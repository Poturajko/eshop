<?php

namespace App\Core\Orm\EntityManager;

use App\Core\Orm\DataMapper\DataMapper;
use App\Core\Orm\QueryBuilder\QueryBuilder;
use Throwable;

class Crud implements ICrud
{
    private DataMapper $dataMapper;

    private QueryBuilder $queryBuilder;

    private string $tableName;

    private string $primaryKey;

    public function __construct(
        DataMapper $dataMapper,
        QueryBuilder $queryBuilder,
        string $tableName,
        string $primaryKey
    ) {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function lastId(): int
    {
        return $this->dataMapper->getLastId();
    }

    public function join(
        array $selectors,
        array $joinSelectors,
        string $joinTo,
        string $joinType,
        string $joinKey,
        array $conditions = [],
        array $parameters = [],
        array $extras = []
    ): ?array {
        $args = [
            'table' => $this->getTableName(),
            'type' => 'join',
            'selectors' => $selectors,
            'join_to_selectors' => $joinSelectors,
            'join_to' => $joinTo,
            'join_type' => $joinType,
            'conditions' => $conditions,
            'params' => $parameters,
            'extras' => $extras,
            'primary_key' => $this->getPrimaryKey(),
            'join_key' => $joinKey,
        ];
        $query = $this->queryBuilder->buildQuery($args)->joinQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : null;
    }


    public function create(array $fields = [], string $table = '')
    {
        $args = [
            'table' => (isset($table) && !empty($table)) ? $table : $this->getTableName(),
            'type' => 'insert',
            'fields' => $fields
        ];
        $query = $this->queryBuilder->buildQuery($args)->insertQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));

        return $this->dataMapper->numRows() == 1;
    }

    public function read(
        array $selectors = [], array $conditions = [],
        array $parameters = [], array $optional = []
    ): ?array {
        $args = [
            'table' => $this->getTableName(),
            'type' => 'select',
            'selectors' => $selectors,
            'conditions' => $conditions,
            'params' => $parameters,
            'extras' => $optional
        ];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));

        return ($this->dataMapper->numRows() > 0) ? $this->dataMapper->results() : null;
    }

    public function update(string $primaryKey, array $fields = []): bool
    {
        $args = [
            'table' => $this->getTableName(),
            'type' => 'update',
            'fields' => $fields,
            'primary_key' => $primaryKey
        ];
        $query = $this->queryBuilder->buildQuery($args)->updateQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));

        return $this->dataMapper->numRows() == 1;
    }

    public function delete(array $conditions = []): bool
    {
        $args = ['table' => $this->getTableName(), 'type' => 'delete', 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));

        return $this->dataMapper->numRows() == 1;
    }

    public function search(array $selectors = [], array $conditions = []): array
    {
        $args = [
            'table' => $this->getTableName(),
            'type' => 'search',
            'selectors' => $selectors,
            'conditions' => $conditions
        ];
        $query = $this->queryBuilder->buildQuery($args)->searchQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));

        return ($this->dataMapper->numRows() >= 0) ? $this->dataMapper->results() : [];
    }

    public function get(array $selectors = [], array $conditions = []): ?object
    {
        $args = [
            'table' => $this->getTableName(),
            'type' => 'select',
            'selectors' => $selectors,
            'conditions' => $conditions
        ];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));

        return ($this->dataMapper->numRows() > 0) ? $this->dataMapper->result() : null;
    }

    public function aggregate(string $type, ?string $field = 'id', array $conditions = [])
    {
        $args = [
            'table' => $this->getTableName(),
            'primary_key' => $this->getPrimaryKey(),
            'type' => 'select',
            'aggregate' => $type,
            'aggregate_field' => $field,
            'conditions' => $conditions
        ];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows() > 0) {
            return $this->dataMapper->column();
        }
    }

    public function countRecords(array $conditions = [], ?string $field = 'id'): int
    {
        if ($this->getPrimaryKey() !== '') {
            return empty($conditions) ? $this->aggregate('COUNT', $this->getPrimaryKey()) : $this->aggregate('COUNT',
                $this->getPrimaryKey(), $conditions);
        }
    }
}