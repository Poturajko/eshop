<?php

namespace App\Core\Orm\Relations;

use App\Core\Base\IBaseModel;
use App\Core\Orm\EntityManager\IEntityManager;
use App\Core\Orm\QueryBuilder\IQueryBuilder;
use App\Core\Orm\QueryBuilder\QueryBuilder;
use UnexpectedValueException;

class RelationFactory
{
    private IEntityManager $em;

    private IBaseModel $model;

    public function __construct(IEntityManager $entityManager, IBaseModel $model)
    {
        $this->em = $entityManager;
        $this->model = $model;
    }

    public function create(string $relationString)
    {
        $relationObject = new $relationString($this->em, $this->model);
        if (!$relationObject instanceof IRelation) {
            throw new UnexpectedValueException($relationString . ' is not a valid relation object.');
        }

        return $relationObject;
    }
}