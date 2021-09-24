<?php

namespace App\Controllers;


use App\Core\Application;
use App\Core\Base\BaseController;
use App\Core\Request\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

class CartController extends BaseController
{
    public function cart()
    {
        $ids = array_keys(session()->get('cart'));
        $products = (new Product())->getRepo()->findByIds(['id' => $ids]);

        $this->render('master', 'cart', compact('products'));
    }

    public function cartAdd(int $id)
    {
        (new Cart())->addProductToCart($id);
        session('success', 'Товар добавлен');

        back();
    }

    public function cartRemove(int $id)
    {
        $result = (new Cart())->removeProductInCart($id);
        if (!$result) {
            redirect('/');
        }
        session('warning', 'Товар удален');

        back();
    }

    public function checkout(Request $request)
    {
        $order = new Order();

        if ($request->isPost()) {
            $order->loadData($request->getBody());
            if ($order->validate() && $order->saveOrder()) {
                session()->delete('cart');
                session('success', 'Заказ оформлен');
                redirect('/');
            }
            $this->render('master', 'checkout', compact('order'));
        }
        $this->render('master', 'checkout', compact('order'));
    }

}