<?php

namespace App\Controllers;


use App\Core\Application;
use App\Core\Base\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

class CartController extends BaseController
{
    public function cart()
    {
        $ids = array_keys(Application::$app->session->get('cart'));
        $products = (new Product())->getRepo()->findByIds(['id' => $ids]);

        $this->render('master', 'cart', compact('products'));
    }

    public function cartAdd(Response $response, $id)
    {
        (new Cart())->addProductToCart($id);
        Application::$app->session->set('success', 'Товар добавлен');

        $response->back();
    }

    public function cartRemove(Response $response, $id)
    {
        $result = (new Cart())->removeProductInCart($id);
        if (!$result) {
            $response->redirect('/');
        }
        Application::$app->session->set('warning', 'Товар удален');

        $response->back();
    }

    public function checkout(Request $request, Response $response)
    {
        $order = new Order();

        if ($request->isPost()) {
            $order->loadData($request->getBody());
            if ($order->validate() && $order->saveOrder()) {
                Application::$app->session->delete('cart');
                Application::$app->session->set('success', 'Заказ оформлен');
                $response->redirect('/');
            }

            $this->render('master', 'checkout', compact('order'));
        }

        $this->render('master', 'checkout', compact('order'));
    }

}