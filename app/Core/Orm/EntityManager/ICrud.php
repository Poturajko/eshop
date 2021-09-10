<?php

namespace App\Core\Orm\EntityManager;

interface ICrud
{

    public function getTableName(): string;

    public function getPrimaryKey(): string;

    public function lastId(): int;

    public function create(array $fields = []);

    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []);

    public function update(string $primaryKey, array $fields = []);

    public function delete(array $conditions = []);

    public function aggregate(string $type, ?string $field = 'id', array $conditions = []);

    public function search(array $selectors = [], array $conditions = []): array;

    public function get(array $selectors = [], array $conditions = []): ?object;

    public function countRecords(array $conditions = [], ?string $field = 'id'): int;
}