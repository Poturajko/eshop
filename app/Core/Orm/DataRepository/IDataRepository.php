<?php

namespace App\Core\Orm\DataRepository;

interface IDataRepository
{
    public function find(int $id): object;

    public function findAll(): array;

    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : ?array;

    public function findOneBy(array $conditions): ?object;

    public function findByIdAndDelete(array $conditions): bool;

    public function findByIdUpdate(int $id, array $fields = []): bool;

    public function lastId() : int;

    public function findWithRelationship(array $selectors, array $joinSelectors, string $joinTo, string $joinType, string $joinKey, array $conditions = [], array $parameters = [], array $extras = []);

}