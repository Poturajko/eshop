<?php

namespace App\Core;


use App\Core\Base\BaseController;
use App\Core\Database\Connection;
use App\Core\Middleware\MiddlewareStack;
use App\Core\Router\IRouter;
use App\Core\Router\Router;
use App\Core\Router\RouterFactory;
use App\Models\User;
use Exception;
use function DI\create;

class Application
{
    public static Application $app;
    private static $layouts = 'main';
    public ?BaseController $controller;
    public IRouter $router;
    private Request $request;
    private Response $response;
    public View $view;
    public Session $session;
    public ?User $user;
    private $userClass;

    public MiddlewareStack $middleware;

    public function __construct($userClass)
    {
        $this->user = null;
        self::$app = $this;
        $this->middleware = new MiddlewareStack();
        $this->response = new Response();
        $this->request = new Request();
        $this->router = (new RouterFactory($this->response, $this->request, $this->middleware))
            ->create(Router::class);
        $this->view = new View();
        $this->session = new Session();

        $this->userClass = $userClass;
        $userId = $this->session->get('user');
        if ($userId) {
            $this->user = $this->userClass->getRepo()->findOneBy(['id' => $userId]);
        }
    }

    public function addMiddleware(string $middleware)
    {
        if (class_exists($middleware)) {
            $instance = new $middleware();
            $this->middleware->add($instance);
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