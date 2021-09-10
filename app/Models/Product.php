<?php


namespace App\Models;


use App\Core\Base\BaseModel;

class Product extends BaseModel
{
    public int $id;
    public string $name;
    public string $code;
    public int $category_id;
    public float $price;
    public string $description;
    public ?string $image;
    public bool $hit;
    public bool $new;
    public bool $recommend;
    public int $count;

    public const TABLE_NAME = 'products';

    public const PRIMARY_KEY = 'id';

    public const FOREIGN_KEY = 'category_id';

    public const FOREIGN_PIVOT_KEY = 'product_id';

    public const PIVOT_TABLE_NAME = 'order_product';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::PRIMARY_KEY, static::class);
    }

    public function attributes(): array
    {
        return [
            'id',
            'name',
            'code',
            'category_id',
            'price',
            'description',
            'image',
            'hit',
            'new',
            'recommend'
        ];
    }

    public function category()
    {
        return $this->getRelation()->belongsTo(Category::class);
    }

    public function isHit()
    {
        return $this->hit == 1;
    }

    public function isNew()
    {
        return $this->new == 1;
    }

    public function isRecommend()
    {
        return $this->recommend == 1;
    }

}