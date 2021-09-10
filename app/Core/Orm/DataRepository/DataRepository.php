<?php

namespace App\Core\Orm\DataRepository;

use App\Core\Orm\EntityManager\IEntityManager;
use App\Core\Request;
use InvalidArgumentException;
use Throwable;

class DataRepository implements IDataRepository
{
    private IEntityManager $em;

    public function __construct(IEntityManager $em)
    {
        $this->em = $em;
    }

    private function isArray(array $conditions): void
    {
        if (!is_array($conditions)) {
            throw new InvalidArgumentException('The argument supplied is not an array');
        }
    }

    private function isEmpty(int $id): void
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Argument should not be empty');
        }
    }

    public function find(int $id): object
    {
        $this->isEmpty($id);
        try {
            return $this->findOneBy(['id' => $id]);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findAll(): array
    {
        try {
            return $this->findBy();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findBy(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): ?array {
        try {
            return $this->em->getCrud()->read($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findByIds(array $conditions = [], array $optional = []): array
    {
        try {
            return $this->findBy([], $conditions, [], $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findOneBy(array $conditions): ?object
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->get([], $conditions);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findBySearch(array $selectors = [], array $conditions = []): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->search($selectors, $conditions);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findByIdAndDelete(array $conditions): bool
    {
        $this->isArray($conditions);
        try {
            $result = $this->findOneBy($conditions);
            if (!is_null($result) && count($result) > 0) {
                $delete = $this->em->getCrud()->delete($conditions);
                if ($delete) {
                    return true;
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findByIdUpdate(int $id, array $fields = []): bool
    {
        $this->isArray($fields);
        try {
            $result = $this->findOneBy([$this->em->getCrud()->getPrimaryKey() => $id]);
            if (!is_null($result) && count($result) > 0) {
                $params = (!empty($fields)) ? array_merge([$this->em->getCrud()->getPrimaryKey() => $id],
                    $fields) : $fields;
                $update = $this->em->getCrud()->update($this->em->getCrud()->getPrimaryKey(), $params);
                if ($update) {
                    return true;
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function update(int $id, array $fields = []): bool
    {
        try {
            $params = array_merge([$this->em->getCrud()->getPrimaryKey() => $id], $fields);
            return $this->em->getCrud()->update($this->em->getCrud()->getPrimaryKey(), $params);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function save(array $fields = [], string $table = ''): bool
    {
        try {
            return $this->em->getCrud()->create($fields, $table);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function lastId(): int
    {
        return $this->em->getCrud()->lastId();
    }

    public function findWithRelationship(
        array $selectors,
        array $joinSelectors,
        string $joinTo,
        string $joinType,
        string $joinKey,
        array $conditions = [],
        array $parameters = [],
        array $extras = []
    ) {
        return $this->em->getCrud()->join($selectors, $joinSelectors, $joinTo, $joinType, $joinKey, $conditions,
            $parameters, $extras);
    }

    public function findWithPaging(Request $request, int $limit = 9, array $conditions = [], array $selectors = []): ?array
    {
        $page = $request->getBody()['page'] ?: 1;
        $offset = ($page - 1) * $limit;

        return $this->findBy($selectors, $conditions, ['limit' => $limit, 'offset' => $offset]);
    }

    public function count(array $conditions = [], ?string $field = 'id')
    {
        return $this->em->getCrud()->countRecords($conditions, $field);
    }
}