<?php

namespace App\Controllers\Admin;

use App\Core\Base\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Models\Order;

class OrderController extends BaseController
{
    public function index()
    {
        $orders = (new Order())->getRepo()->findBy([], ['status' => 1]);

        $this->render('auth', 'auth/orders/index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = (new Order())->getRepo()->find($id);

        $this->render('auth', 'auth/orders/show', compact('order'));
    }
}