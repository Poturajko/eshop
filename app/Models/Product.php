<?php


namespace App\Models;


class Product extends \App\Model
{
    private string $table = 'products';

    public function getProducts(): ?array
    {
        return $this->db->rows('SELECT * FROM ' . $this->table);
    }

    public function getProductsByCategoryId(string $categoryId): ?array
    {
        $params = [
            'category_id' => $categoryId,
        ];

        return $this->db->rows('SELECT * FROM ' . $this->table . ' WHERE category_id = :category_id',$params);
    }
}