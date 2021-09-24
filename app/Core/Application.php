<?php

namespace App\Core;


use App\Core\Base\BaseController;
use App\Core\Middleware\MiddlewareStack;
use App\Core\Request\IRequest;
use App\Core\Request\Request;
use App\Core\Request\RequestFactory;
use App\Core\Response\IResponse;
use App\Core\Response\Response;
use App\Core\Response\ResponseFactory;
use App\Core\Router\IRouter;
use App\Core\Router\Router;
use App\Core\Router\RouterFactory;
use App\Core\Session\ISession;
use App\Core\Session\Session;
use App\Core\Session\SessionFactory;
use App\Core\View\IView;
use App\Core\View\View;
use App\Core\View\ViewFactory;
use App\Models\User;
use Exception;

class Application
{
    public static Application $app;
    private static $layouts = 'main';
    public ?BaseController $controller;
    public IRouter $router;
    private IRequest $request;
    private IResponse $response;
    public IView $view;
    public ISession $session;
    public ?User $user;
    private $userClass;

    public MiddlewareStack $middleware;

    public function __construct($userClass)
    {
        $this->user = null;
        self::$app = $this;
        $this->middleware = new MiddlewareStack();
        $this->response = (new ResponseFactory())->create(Response::class);
        $this->request = (new RequestFactory())->create(Request::class);
        $this->router = (new RouterFactory($this->response, $this->request, $this->middleware))->create(Router::class);
        $this->view = (new ViewFactory())->create(View::class);
        $this->session = (new SessionFactory())->create(Session::class);

        $this->userClass = $userClass;
        $userId = $this->session->get('user');
        if ($userId) {
            $this->user = $this->userClass->getRepo()->findOneBy(['id' => $userId]);
        }
        $this->loadCoreFunctions();
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

    public function loadCoreFunctions(): void
    {
        foreach (glob(HELPERS_DIR . DS . 'functions' . DS . '*.php') as $filename) {
            require_once $filename;
        }
    }

}