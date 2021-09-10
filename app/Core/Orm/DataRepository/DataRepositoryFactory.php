<?php

namespace App\Core\Orm\DataRepository;

use App\Core\Orm\EntityManager\IEntityManager;
use App\Core\Orm\OrmManager;
use http\Exception\UnexpectedValueException;

class DataRepositoryFactory
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

    public function create(string $dataRepository)
    {
        $entityManagerObject = $this->initializeOrmManager();
        $dataRepositoryObject = new $dataRepository($entityManagerObject);
        if (!$dataRepositoryObject instanceof IDataRepository) {
            throw new UnexpectedValueException($dataRepository . ' is not a valid repository object.');
        }

        return $dataRepositoryObject;
    }

    private function initializeOrmManager(): IEntityManager
    {
        return (new OrmManager($this->tableName, $this->primaryKey, $this->className))->initialize();
    }
}