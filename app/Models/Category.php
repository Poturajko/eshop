<?php


namespace App\Models;


use App\Core\Base\BaseModel;

class Category extends BaseModel
{
    public int $id;
    public string $code;
    public string $name;
    public string $description;
    public $image;

    public const TABLE_NAME = 'categories';

    public const PRIMARY_KEY = 'id';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::PRIMARY_KEY, static::class);
    }

    public function rules(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [
            'id',
            'code',
            'name',
            'description',
            'image'
        ];
    }

    public function products()
    {
        return $this->getRelation()->hasMany(Product::class);
    }
}