<?php

namespace App\Controllers\User;

use App\Core\Application;
use App\Core\Base\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Models\Order;

class OrderController extends BaseController
{
    public function index()
    {
        $userId = Application::$app->user->id;
        $orders = (new Order())->getRepo()->findBy([], ['user_id' => $userId]);

        $this->render('auth', 'auth/orders/index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = (new Order())->getRepo()->find($id);

        $this->render('auth', 'auth/orders/show', compact('order'));
    }
}