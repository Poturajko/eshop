<?php

namespace App\Core\Base;

use App\Core\Auth\Model;
use App\Core\Orm\DataRepository\DataRepository;
use App\Core\Orm\DataRepository\DataRepositoryFactory;
use App\Core\Orm\DataRepository\IDataRepository;
use App\Core\Orm\EntityManager\EntityManager;
use App\Core\Orm\EntityManager\IEntityManager;
use App\Core\Orm\OrmManager;
use App\Core\Orm\Relations\IRelation;
use App\Core\Orm\Relations\Relation;
use App\Core\Orm\Relations\RelationFactory;
use InvalidArgumentException;


class BaseModel extends Model implements IBaseModel
{
    public const SHOW_BY_DEFAULT = 6;

    private DataRepository $repository;

    private EntityManager $em;

    private Relation $relation;

    public function __construct(string $tableName, string $primaryKey, string $className)
    {
        if (empty($tableName) || empty($primaryKey) || empty($className)) {
            throw new InvalidArgumentException('These arguments are required.');
        }
        if (!class_exists($className)) {
            throw new InvalidArgumentException($className . ' is not valid class name.');
        }
        $this->repository = (new DataRepositoryFactory($tableName, $primaryKey, $className))
            ->create(DataRepository::class);
        $this->em = (new OrmManager($tableName, $primaryKey, $className))
            ->initialize();
        $this->relation = (new RelationFactory($this->em, $this))
            ->create(Relation::class);
    }

    public function getRepo(): IDataRepository
    {
        return $this->repository;
    }

    public function getEm(): IEntityManager
    {
        return $this->em;
    }

    public function getRelation(): IRelation
    {
        return $this->relation;
    }
}