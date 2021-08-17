<?php

namespace App\Models;


use App\Core\BaseModel;

class User extends BaseModel
{
    public string $id = '';
    public string $is_admin = '';
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['id', 'is_admin', 'name', 'email', 'password'];
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirmation' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function create(array $params = []): ?string
    {
        unset($params['passwordConfirmation']);
        $params['password'] = password_hash($this->password, PASSWORD_BCRYPT);
        return parent::create($params);
    }

}