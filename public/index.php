<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->get('/', [\App\Controllers\MainController::class, 'index']);

$router->get('/categories', [\App\Controllers\MainController::class, 'categories']);
$router->get('/categories/{code}/{id}', [\App\Controllers\MainController::class, 'categories']);

$router->get('/{code}', [\App\Controllers\MainController::class, 'category']);
$router->get('/{code}/{id}', [\App\Controllers\MainController::class, 'category']);

$router->addNotFoundHandler(function () {
    $title = 'Not Found!';
    require_once __DIR__ . '/../resources/errors/404.php';
});

$router->run();