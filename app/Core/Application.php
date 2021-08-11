<?php

namespace App;

use function Composer\Autoload\includeFile;

class Application
{
    public static Application $app;
    public string $layout = 'master';
    public Router $router;
    public Request $request;
    public Response $response;
    public View $view;
    public Session $session;
    public Database $db;
    public ?array $user;
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
        $this->db = new Database();
        $this->userClass = $userClass;

        $userId = $this->session->get('user');
        if ($userId) {
            $this->user = $this->userClass->findOne(['id' => $userId]);
        }
    }

    public function run(): void
    {
        $this->router->resolve();
    }

    public function login(array $user): bool
    {
        $this->user = $user;
        $this->session->set('user', $user['id']);

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
        return self::$app->user['is_admin'];
    }
}