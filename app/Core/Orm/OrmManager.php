<?php

namespace App\Core\Orm;

use App\Core\Database\Connection;
use App\Core\Orm\DataMapper\DataMapperFactory;
use App\Core\Orm\EntityManager\Crud;
use App\Core\Orm\EntityManager\EntityManagerFactory;
use App\Core\Orm\EntityManager\IEntityManager;
use App\Core\Orm\QueryBuilder\QueryBuilder;
use App\Core\Orm\QueryBuilder\QueryBuilderFactory;

class OrmManager
{
    private string $tableName;

    private string $primaryKey;

    private string $className;

    public function __construct(string $tableName, string $primaryKey, string $className)
    {
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
    }

    public function initialize(): IEntityManager
    {
        $dataMapperObject = (new DataMapperFactory())->create(Connection::class, $this->className);
        if ($dataMapperObject) {
            $queryBuilderObject = (new QueryBuilderFactory())->create(QueryBuilder::class);
            if ($queryBuilderObject) {
                $entityManagerObject = (new EntityManagerFactory($dataMapperObject, $queryBuilderObject))
                    ->create(Crud::class, $this->tableName, $this->primaryKey);

                return $entityManagerObject;
            }
        }
    }
}