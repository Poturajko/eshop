<?php

namespace App\Core\Orm\QueryBuilder;


use InvalidArgumentException;

class QueryBuilder implements IQueryBuilder
{
    private array $key;

    private string $sqlQuery;

    private const SQL_DEFAULT = [
        'conditions' => [],
        'selectors' => [],
        'replace' => false,
        'distinct' => false,
        'from' => [],
        'where' => null,
        'and' => [],
        'or' => [],
        'orderby' => [],
        'fields' => [],
        'primary_key' => '',
        'table' => '',
        'type' => '',
        'raw' => '',

        'join_to' => '',
        'join_key' => '',
        'join_to_selectors' => [],
        'join_type' => '',
    ];

    private const QUERY_TYPES = ['insert', 'select', 'update', 'delete', 'search', 'join'];

    public function buildQuery(array $args = []): self
    {
        if (count($args) < 0) {
            throw new InvalidArgumentException('Your BuildQuery method has no defined argument. Please fix this');
        }
        $arg = array_merge(self::SQL_DEFAULT, $args);
        $this->key = $arg;

        return $this;
    }

    private function isQueryTypeValid(string $type): bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }

        return false;
    }

    public function insertQuery(): string
    {
        if ($this->isQueryTypeValid('insert')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $index = array_keys($this->key['fields']);
                $value = [implode(', ', $index), ":" . implode(', :', $index)];
                $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$value[0]}) VALUES ({$value[1]})";

                return $this->sqlQuery;
            }
        }
    }

    public function selectQuery(): string
    {
        if ($this->isQueryTypeValid('select')) {
            $selectors = (!empty($this->key['selectors'])) ? implode(', ', $this->key['selectors']) : '*';
            if (isset($this->key['aggregate']) && $this->key['aggregate']) {
                $this->sqlQuery = "SELECT {$this->key['aggregate']}({$this->key['aggregate_field']}) FROM {$this->key['table']}";
            } else {
                $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";
            }
            $this->sqlQuery = $this->hasConditions();

            return $this->sqlQuery;
        }
    }

    public function deleteQuery(): string
    {
        if ($this->isQueryTypeValid('delete')) {
            $index = array_keys($this->key['conditions']);
            $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]}";
            if (isset($this->key['conditions']) && count($this->key['conditions']) > 1) {
                $this->sqlQuery .= " AND {$index[1]} = :{$index[1]}";
            }
            $this->sqlQuery .= ' LIMIT 1';

            return $this->sqlQuery;
        }
    }

    public function updateQuery(): string
    {
        if ($this->isQueryTypeValid('update')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $values = '';
                foreach (array_keys($this->key['fields']) as $field) {
                    if ($field !== $this->key['primary_key']) {
                        $values .= $field . " = :" . $field . ", ";
                    }
                }
                $values = substr_replace($values, '', -2);
                if (count($this->key['fields']) > 0) {
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values} WHERE {$this->key['primary_key']} = :{$this->key['primary_key']} LIMIT 1";
                    if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
                        unset($this->key['primary_key']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values}";
                    }
                }
                return $this->sqlQuery;
            }
        }
    }

    public function searchQuery(): string
    {
        if ($this->isQueryTypeValid('search')) {
            if (is_array($this->key['selectors']) && $this->key['selectors'] !== '') {
                $this->sqlQuery = "SELECT * FROM {$this->key['table']} WHERE ";
                if ($this->has('selectors')) {
                    $values = [];
                    foreach ($this->key['selectors'] as $selector) {
                        $values[] = $selector . " LIKE " . ":{$selector}";
                    }
                    if (count($this->key['selectors']) >= 1) {
                        $this->sqlQuery .= implode(" OR ", $values);
                    }
                }
            }

            return $this->sqlQuery;
        }
    }


    private function hasConditions()
    {
        if (isset($this->key['conditions']) && $this->key['conditions'] !== '') {
            if (is_array($this->key['conditions'])) {
                $sort = [];
                $val = '';
                foreach (array_keys($this->key['conditions']) as $where) {
                    if (isset($where) && $where !== '') {
                        if (is_array($this->key['conditions'][$where])) {
                            foreach ($this->key['conditions'][$where] as $operand => $value) {
                                if (count($this->key['conditions']) >= 1) {
                                    if (!is_int($operand)) {
                                        $sort [] = $where . " $operand :" . $value;
                                    }else{
                                        $val .= ":$operand,";
                                        $sort [0] = $where . ' IN ' . '(' . rtrim($val, ',') . ')';
                                    }
                                } else {
                                    $sort [] = $where . " $operand :" . $value;
                                }
                            }
                        } else {
                            $sort[] = $where . " = :" . $where;
                        }
                    }
                }
                if (count($this->key['conditions']) > 0) {
                    $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
                }
            }
        } else {
            if (empty($this->key['conditions'])) {
                $this->sqlQuery = " WHERE 1";
            }
        }

        $this->sqlQuery .= $this->orderByQuery();
        $this->sqlQuery .= $this->queryOffset();

        return $this->sqlQuery;
    }

    protected function orderByQuery(): void
    {
        if (isset($this->key["extras"]["orderby"]) && $this->key["extras"]["orderby"] != "") {
            $this->sqlQuery .= " ORDER BY " . $this->key["extras"]["orderby"] . " ";
        }
    }

    protected function queryOffset(): void
    {
        if (isset($this->key["params"]["limit"]) && $this->key["params"]["offset"] != -1) {
            $this->sqlQuery .= " LIMIT :offset, :limit";
        }

    }

    private function has(string $key): bool
    {
        return isset($this->key[$key]);
    }

    public function joinQuery(): string
    {

        if ($this->isQueryTypeValid('join')) {
            if (isset($this->key['selectors']) && count($this->key['selectors']) > 0) {
                $selectors = implode(', ', $this->aliasSelectors($this->key['table'], $this->key['selectors']));
            }
            if (isset($this->key['join_to_selectors']) && count($this->key['join_to_selectors']) > 0) {
                $joinSelectors = implode(', ',
                    $this->aliasSelectors($this->key['join_to'], $this->key['join_to_selectors']));
            }
            $this->sqlQuery = "SELECT {$selectors}, {$joinSelectors} 
                                    FROM {$this->key['table']} {$this->key['join_type']} {$this->key['join_to']} 
                                        ON {$this->key['table']}.{$this->key['primary_key']} = {$this->key['join_to']}.{$this->key['join_key']}";

            $this->sqlQuery = $this->hasConditions();

            return $this->sqlQuery;
        }

    }

    public function aliasSelectors(string $parent, array $selectors, $default = ['*']): array
    {
        $filter = array_map(fn($selector): string => $parent . '.' . $selector, $selectors);
        return (empty($filter)) ? $default : $filter;
    }
}