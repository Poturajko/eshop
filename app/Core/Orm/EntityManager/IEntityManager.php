<?php

namespace App\Core\Orm\EntityManager;

use App\Core\Orm\DataMapper\IDataMapper;
use App\Core\Orm\QueryBuilder\IQueryBuilder;

interface IEntityManager
{
    public function getCrud(): ICrud;

    public function getQueryBuilder(): IQueryBuilder;

    public function getDataMapper(): IDataMapper;
}