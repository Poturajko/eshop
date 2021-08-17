<?php


namespace App\Models;


use App\Core\BaseModel;

class Category extends BaseModel
{
    public string $id = '';
    public string $code = '';
    public string $name = '';
    public string $description = '';
    public ?string $image = '';

    public function primaryKey(): string
    {
        return 'id';
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

    public function tableName(): string
    {
        return 'categories';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}