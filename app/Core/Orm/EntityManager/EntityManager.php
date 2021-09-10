<?php

namespace App\Core\Orm\EntityManager;

use App\Core\Orm\DataMapper\IDataMapper;
use App\Core\Orm\QueryBuilder\IQueryBuilder;

class EntityManager implements IEntityManager
{
    private ICrud $crud;

    private IQueryBuilder $queryBuilder;

    private IDataMapper $dataMapper;

    public function __construct(ICrud $crud, IQueryBuilder $queryBuilder, IDataMapper $dataMapper)
    {
        $this->crud = $crud;
        $this->queryBuilder = $queryBuilder;
        $this->dataMapper = $dataMapper;
    }

    public function getCrud(): ICrud
    {
        return $this->crud;
    }

    public function getQueryBuilder(): IQueryBuilder
    {
        return $this->queryBuilder;
    }

    public function getDataMapper(): IDataMapper
    {
        return $this->dataMapper;
    }

}