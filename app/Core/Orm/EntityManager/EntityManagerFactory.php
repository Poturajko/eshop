<?php

namespace App\Core\Orm\EntityManager;

use App\Core\Orm\DataMapper\IDataMapper;
use App\Core\Orm\QueryBuilder\IQueryBuilder;
use UnexpectedValueException;


class EntityManagerFactory
{
    private IDataMapper $dataMapper;

    private IQueryBuilder $queryBuilder;

    public function __construct(IDataMapper $dataMapper, IQueryBuilder $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    public function create(string $crud, string $tableName, string $primaryKey): IEntityManager
    {
        $crudObject = new $crud($this->dataMapper, $this->queryBuilder, $tableName, $primaryKey);
        if (!$crudObject instanceof ICrud) {
            throw new UnexpectedValueException($crud . ' is not a valid crud object.');
        }

        return new EntityManager($crudObject, $this->queryBuilder, $this->dataMapper);
    }

}