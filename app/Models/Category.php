<?php


namespace App\Models;


class Category extends \App\Model
{
    private string $table = 'categories';

    public function getCategories(): ?array
    {
        return $this->db->rows('SELECT * FROM ' . $this->table);
    }

    public function getCategoryByCode(string $code): ?array
    {
        $params = [
            'code' => $code,
        ];
        return $this->db->row('SELECT * FROM ' . $this->table . ' WHERE code = :code', $params);
    }

}