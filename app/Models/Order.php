<?php

namespace App\Models;


use App\Core\Application;
use App\Core\Base\BaseModel;

class Order extends BaseModel
{
    public int $id;
    public string $name;
    public ?int $user_id;
    public string $phone;
    public string $email;
    public bool $status;

    public const TABLE_NAME = 'orders';

    public const PRIMARY_KEY = 'id';

    public const FOREIGN_PIVOT_KEY = 'order_id';

    public const PIVOT_TABLE = 'order_product';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::PRIMARY_KEY, static::class);
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

    public function products()
    {
        return $this->getRelation()->belongsToMany(Product::class);
    }

    public function saveOrder(): bool
    {
        $this->user_id = !is_null(auth()) ? Application::$app->user->id : null;
        $fields = [
            'status' => 1,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'user_id' => $this->user_id,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->getRepo()->save($fields)) {
            $orderId = $this->getRepo()->lastId();
            foreach (session()->get('cart') as $productId => $qnt) {
                $this->getRepo()->save([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'count' => $qnt,
                ], self::PIVOT_TABLE);
            }

            return true;
        }

        return false;
    }

    public function getFullPrice()
    {
        foreach ($this->products() as $product) {
            $sum += $product->price * $product->count;
        }

        return $sum;
    }

    public function getPriceForCount(float $price, int $count)
    {
        return $price * $count;
    }
}