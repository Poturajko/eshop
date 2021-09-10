<?php

namespace App\Core\Orm\DataMapper;

class DataMapperFactory
{
    public function create(string $dbConnection, string $className): IDataMapper
    {
        $dbConnectionObject = $dbConnection::getInstance();

        return new DataMapper($dbConnectionObject, $className);
    }
}