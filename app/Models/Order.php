<?php

namespace App\Models;


use App\Core\Application;
use App\Core\BaseModel;

class Order extends BaseModel
{
    public string $id = '';
    public string $name = '';
    public string $user_id = '';
    public string $phone = '';
    public string $email = '';
    public string $status = '';

    public function primaryKey()
    {
        return 'id';
    }

    public function foreignPivotKey()
    {
        return 'order_id';
    }

    public function pivotTable()
    {
        return 'order_product';
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'phone' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
        ];
    }

    public function attributes(): array
    {
        return ['id', 'user_id', 'name', 'phone', 'email', 'status'];
    }

    public function tableName(): string
    {
        return 'orders';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function saveOrder(): bool
    {
        $this->status = 1;
        if (Application::auth() !== null) {
            $this->user_id = Application::$app->user->id;
        }
        $orderId = $this->create();
        foreach (Application::$app->session->get('cart') as $productId => $qnt) {
            $params = ['order_id' => $orderId, 'product_id' => $productId, 'count' => $qnt];
            $this->save('order_product', ['order_id', 'product_id', 'count'], $params);
        }

        return true;
    }

    public function getFullPrice()
    {
        foreach ($this->products() as $product) {
            $sum += $product->price;
        }

        return $sum;
    }
}