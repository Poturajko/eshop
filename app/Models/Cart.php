<?php

namespace App\Models;

use App\Core\Application;

class Cart
{
    public function addProductToCart(int $id): void
    {
        $productsInCart = Application::$app->session->get('cart');
        (array_key_exists($id, $productsInCart)) ? $productsInCart[$id]++ : $productsInCart[$id] = 1;
        $_SESSION['cart'] = $productsInCart;
    }

    public function removeProductInCart(int $id): bool
    {
        $productsInCart = Application::$app->session->get('cart');
        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id]--;
            if (empty($productsInCart[$id])) {
                unset($productsInCart[$id]);
            }
        }
        $_SESSION['cart'] = $productsInCart;
        if (empty($productsInCart)) {
            unset($productsInCart);
            return false;
        }

        return true;
    }

    public function countItems(int $productId): int
    {
        $productsInCart = Application::$app->session->get('cart');
        $product = [];
        foreach ($productsInCart as $id => $qnt) {
            if ($id === $productId) {
                $product[$productId] += $qnt;
            }
        }

        return $product[$productId];
    }

    public function getTotalPrice(int $productId, int $productPrice): int
    {
        $qntProduct = $this->countItems($productId);

        return $productPrice * $qntProduct;
    }

    public function getFullSum(): int
    {
        $productsInCart = Application::$app->session->get('cart');
        $idsArray = array_keys($productsInCart);
        $products = (new Product())->whereIn('id',$idsArray)->get();

        $sum = 0;
        foreach ($products as $product) {
            $sum += $product->price * $productsInCart[$product->id];
        }

        return (int)$sum;
    }

}