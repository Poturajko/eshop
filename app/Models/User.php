<?php

namespace App\Models;


use App\Core\Base\BaseModel;
use http\Message\Body;

class User extends BaseModel
{
    public int $id;
    public int $is_admin;
    public string $name;
    public string $email;
    public string $password;
    public string $passwordConfirmation;

    public const TABLE_NAME = 'users';

    public const PRIMARY_KEY = 'id';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::PRIMARY_KEY, static::class);
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

    public function create(array $params = []): bool
    {
        return $this->getRepo()->save([
            'is_admin' => 0,
            'name' => $this->name,
            'email' => $this->email,
            'password' => password_hash($this->password, PASSWORD_BCRYPT),
        ]);
    }

}