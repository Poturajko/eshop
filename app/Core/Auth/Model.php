<?php

namespace App\Core\Auth;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, 'Поле обязательно для заполнения');
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, 'Не корректный e-mail адресс');
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, "Поле должно содержать минимум {$rule['min']} символов.");
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addError($attribute, 'Поле должно совпадать с полем пароль');
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = new $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $user = $className->getRepo()->findOneBy([$uniqueAttribute => $value]);
                    if (isset($user)) {
                        $this->addError($attribute, 'Такая запись уже существует');
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError(string $attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError(string $attribute): string
    {
        $errors = $this->errors[$attribute] ?? [];
        return $errors[0] ?? '';
    }

}