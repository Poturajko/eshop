<?php

namespace App\Models;

use App\Core\Application;
use App\Core\Model;

class LoginForm extends Model
{
    public string $email;
    public string $password;

    public function rules(): array
    {
        return [
            'password' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
        ];
    }

    public function login(): bool
    {
        $user = (new User())->getRepo()->findOneBy(['email' => $this->email]);
        if (!$user) {
            $this->addError('email', 'Пользователя с таким e-mail не существует');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Не верный пароль');
            return false;
        }

        return Application::$app->login($user);
    }

    public function attributes(): array
    {
        return ['email', 'password'];
    }
}