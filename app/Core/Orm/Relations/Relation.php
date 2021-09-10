<?php

namespace App\Core\Orm\Relations;

use App\Core\Base\IBaseModel;
use App\Core\Orm\EntityManager\IEntityManager;

class Relation implements IRelation
{
    private IEntityManager $em;

    private IBaseModel $parent;

    public function __construct(IEntityManager $entityManager, IBaseModel $parent)
    {
        $this->em = $entityManager;
        $this->parent = $parent;
    }

    public function hasMany(string $related): ?array
    {
        $instance = new $related;

        return $instance->getRepo()->findBy([], [$instance::FOREIGN_KEY => $this->parent->id]);
    }

    public function belongsTo(string $related): object
    {
        $instance = new $related;

        return $instance->getRepo()->findOneBy([$instance::PRIMARY_KEY => $this->parent->category_id]);
    }

    public function belongsToMany(string $related): array
    {
        $instance = new $related();

        return $instance->getRepo()->findWithRelationship(
            $instance->attributes(), ['count'],
            $instance::PIVOT_TABLE_NAME, 'LEFT JOIN', $instance::FOREIGN_PIVOT_KEY,
            ['order_id' => $this->parent->id],
        );
    }
}