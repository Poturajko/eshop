<?php

namespace App\Models;

use App\Core\Application;
use App\Core\BaseModel;

class LoginForm extends BaseModel
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
        $user = (new User())->where('email', $this->email)->first();
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

    public function tableName(): string
    {
        return '';
    }
}