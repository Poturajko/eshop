<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = (new Order())->where('status', 1)->get();

        $this->render('auth', 'auth/orders/index', compact('orders'));
    }

    public function show(Request $request, Response $response, int $id)
    {
        $order = (new Order())->where('id', $id)->first();

        $this->render('auth', 'auth/orders/show', compact('order'));
    }
}