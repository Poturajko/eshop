<?php

namespace App\Controllers;


use App\Core\Application;
use App\Core\Base\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Models\LoginForm;
use App\Models\User;

class AuthController extends BaseController
{
    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();

        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
            }
            $this->render('auth', 'login', compact('loginForm'));
        }
        $this->render('auth', 'login', compact('loginForm'));
    }

    public function register(Request $request, Response $response)
    {
        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate()) {
                $userId = $user->create();
                Application::$app->session->set('user', $userId);
                $response->redirect('/');
            }
            $this->render('auth', 'register', compact('user'));
        }
        $this->render('auth', 'register', compact('user'));
    }

    public function logout(Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }

}