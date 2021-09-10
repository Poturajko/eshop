<?php

namespace App\Core\Orm\DataMapper;

use PDOStatement;

interface IDataMapper
{
    public function prepare(string $query): IDataMapper;

    public function bind($value);

    public function bindValues(array $fields): PDOStatement;

    public function execute(): bool;

    public function result(): object;

    public function results(): array;

    public function column();

    public function getLastId(): int;

    public function buildQueryParameters(array $conditions = [], array $parameters = []): array;

    public function persist(string $query, array $parameters): bool;
}