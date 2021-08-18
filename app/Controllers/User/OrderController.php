<?php

namespace App\Controllers\User;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Application::$app->user->id;
        $orders = (new Order())->where('user_id', $userId)->get();

        $this->render('auth', 'auth/orders/index', compact('orders'));
    }

    public function show(Request $request,Response $response, int $id)
    {
        $order = (new Order())->where('id', $id)->first();

        $this->render('auth', 'auth/orders/show', compact('order'));
    }
}