<?php


namespace App\Models;


use App\Core\Application;
use App\Core\BaseModel;
use App\Core\Model;

class Product extends BaseModel
{
    public string $id;
    public string $name;
    public string $code;
    public string $category_id;
    public string $price;
    public string $description;
    public ?string $image;
    public bool $hit;
    public bool $new;
    public bool $recommend;

    public function primaryKey(): string
    {
        return 'id';
    }

    public function foreignKey(): string
    {
        return 'category_id';
    }

    public function foreignPivotKey(): string
    {
        return 'product_id';
    }

    public function attributes(): array
    {
        return [
            'id','name','code', 'category_id','price','description','image','hit','new','recommend'
        ];
    }

    public function rules(): array
    {
        return [];
    }

    public function tableName(): string
    {
        return 'products';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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