<?php

namespace App\Core\Orm\QueryBuilder;

use http\Exception\UnexpectedValueException;

class QueryBuilderFactory
{
    public function create(string $queryBuilder):IQueryBuilder
    {
        $queryBuilderObject = new $queryBuilder();
        if (!$queryBuilderObject instanceof IQueryBuilder){
            throw new UnexpectedValueException($queryBuilder . ' is not a valid Query builder object.');
        }

        return $queryBuilderObject;
    }
}