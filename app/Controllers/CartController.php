<?php

namespace App\Controllers;


use App\Core\Application;
use App\Core\Controller;
use App\Core\Middleware\CartMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

class CartController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new CartMiddleware(['cart', 'checkout']));
    }

    public function cart()
    {
        $idsArray = array_keys(Application::$app->session->get('cart'));
        $products = (new Product())->whereIn('id', $idsArray)->get();

        $this->render($this->layout, 'cart', compact('products'));
    }

    public function cartAdd(Request $request, Response $response, $id)
    {
        (new Cart())->addProductToCart($id);
        Application::$app->session->set('success', 'Товар добавлен');

        $response->back();
    }

    public function cartRemove(Request $request, Response $response, $id)
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

            $this->render($this->layout, 'checkout', compact('order'));
        }

        $this->render($this->layout, 'checkout', compact('order'));
    }

}