<?php
ini_set('display_errors', 1);
error_reporting(E_WARNING|E_ERROR);

use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\ProductController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\MainController;
use App\Core\Application;
use App\Middleware\CartIsEmpty;
use App\Middleware\CheckIsAdmin;
use DevCoder\DotEnv;

require_once __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../bootstrap/container.php';
$container->get('const');
(new DotEnv(ENV))->load();

$app = $container->get(Application::class);

$app->addMiddleware(CartIsEmpty::class);
$app->addMiddleware(CheckIsAdmin::class);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/admin/categories', [CategoryController::class, 'index']);
$app->router->post('/admin/categories', [CategoryController::class, 'store']);
$app->router->get('/admin/categories/create', [CategoryController::class, 'create']);
$app->router->get('/admin/categories/{id}', [CategoryController::class, 'show']);
$app->router->get('/admin/{id}/categories', [CategoryController::class, 'edit']);
$app->router->post('/admin/categories/delete/{id}', [CategoryController::class, 'destroy']);
$app->router->post('/admin/categories/{id}', [CategoryController::class, 'update']);

$app->router->get('/admin/products', [ProductController::class, 'index']);
$app->router->post('/admin/products', [ProductController::class, 'store']);
$app->router->get('/admin/products/create', [ProductController::class, 'create']);
$app->router->get('/admin/products/{id}', [ProductController::class, 'show']);
$app->router->get('/admin/{id}/products', [ProductController::class, 'edit']);
$app->router->post('/admin/products/delete/{id}', [ProductController::class, 'destroy']);
$app->router->post('/admin/products/{id}', [ProductController::class, 'update']);

$app->router->get('/admin/orders', [OrderController::class, 'index']);
$app->router->get('/admin/orders/{order}', [OrderController::class, 'show']);

$app->router->get('/user/orders', [\App\Controllers\User\OrderController::class, 'index']);
$app->router->get('/user/orders/{order}', [\App\Controllers\User\OrderController::class, 'show']);

$app->router->get('/', [MainController::class, 'index']);
$app->router->get('/categories', [MainController::class, 'categories']);

$app->router->get('/cart/add/{id}', [CartController::class, 'cartAdd']);
$app->router->get('/cart/remove/{id}', [CartController::class, 'cartRemove']);
$app->router->get('/cart', [CartController::class, 'cart']);
$app->router->get('/checkout', [CartController::class, 'checkout']);
$app->router->post('/checkout', [CartController::class, 'checkout']);

$app->router->get('/{code}/{productId}', [MainController::class, 'product']);
$app->router->get('/{code}', [MainController::class, 'category']);

$app->run();