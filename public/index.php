<?php
ini_set('display_errors', 1);
error_reporting(E_WARNING);
error_reporting(E_ERROR);

use App\Controllers\Admin\CategoryController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\MainController;
use App\Core\Application;
use DevCoder\DotEnv;

require_once __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../bootstrap/container.php';
$const = $container->get('const');

(new DotEnv(ENV))->load();

$app = $container->get(Application::class);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/admin', [CategoryController::class, 'index']);
$app->router->post('/admin/category', [CategoryController::class, 'store']);
$app->router->get('/admin/category/create', [CategoryController::class, 'create']);
$app->router->get('/admin/category/{id}', [CategoryController::class, 'show']);


$app->router->get('/', [MainController::class, 'index']);
$app->router->get('/categories', [MainController::class, 'categories']);

$app->router->get('/cart/add/{id}', [CartController::class, 'cartAdd']);
$app->router->get('/cart/remove/{id}', [CartController::class, 'cartRemove']);
$app->router->get('/cart', [CartController::class, 'cart']);
$app->router->get('/checkout', [CartController::class, 'checkout']);
$app->router->post('/checkout', [CartController::class, 'checkout']);

$app->router->get('/{code}', [MainController::class, 'category']);
$app->router->get('/{code}/{productId}', [MainController::class, 'product']);

$app->run();