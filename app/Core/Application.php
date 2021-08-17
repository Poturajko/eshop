<?php

namespace App\Core;


use App\Core\Database\Connection;
use App\Models\User;
use Exception;
use PDO;

class Application
{
    public static Application $app;
    public static $layouts = 'main';
    public ?Controller $controller;
    public Router $router;
    public Request $request;
    public Response $response;
    public View $view;
    public Session $session;
    public PDO $db;
    public ?User $user;
    public $userClass;

    public function __construct($userClass)
    {
        $this->user = null;
        self::$app = $this;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->session = new Session();
        $this->db = Connection::connect();

        $this->userClass = $userClass;
        $userId = $this->session->get('user');
        if ($userId) {
            $this->user = $this->userClass->where('id', $userId)->first();
        }
    }

    public function run(): void
    {
        try {
            $this->router->resolve();
        } catch (Exception $exception) {
            $this->response->setStatusCode((int)$exception->getCode());
            echo $this->view->renderView(self::$layouts, 'errors/error', compact('exception'));
        }

    }

    public function login(User $user): bool
    {
        $this->user = $user;
        $this->session->set('user', $user->id);

        return true;
    }

    public function logout(): bool
    {
        $this->session->delete('user');
        $this->response->redirect('/');

        return true;
    }

    public static function auth()
    {
        return self::$app->session->get('user');
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public static function isAdmin()
    {
        return self::$app->user->is_admin;
    }

    public static function routeActive(string $actionName): bool
    {
        foreach (Application::$app->controller as $item) {
            if ($actionName === $item) {
                return true;
            }
        }
        return false;
    }

    public static function cartIsEmpty()
    {
        return empty(self::$app->session->get('cart'));
    }
}