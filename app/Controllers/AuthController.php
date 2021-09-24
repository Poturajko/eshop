<?php

namespace App\Controllers;


use App\Core\Application;
use App\Core\Base\BaseController;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Models\LoginForm;
use App\Models\User;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $loginForm = new LoginForm();

        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                redirect('/');
            }
            $this->render('auth', 'login', compact('loginForm'));
        }
        $this->render('auth', 'login', compact('loginForm'));
    }

    public function register(Request $request)
    {
        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user->create()) {
                $userId = $user->getRepo()->lastId();
                session()->set('user', $userId);
                redirect('/');
            }
            $this->render('auth', 'register', compact('user'));
        }
        $this->render('auth', 'register', compact('user'));
    }

    public function logout()
    {
        session()->delete('user');
        redirect('/');
    }

}