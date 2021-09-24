<?php

namespace App\Core\Mailer;

use DevCoder\DotEnv;
use InvalidArgumentException;

class MailerFactory
{
    private ?array $settings = null;

    public function __construct(?array $settings = null)
    {
        $this->settings = $settings;
    }

    public function create(string $transportString) : IMailer
    {
        $transporterObject = new $transportString(true);
        if (!$transporterObject) {
            throw new InvalidArgumentException($transportString . ' is not a valid mailer object');
        }

        return new Mailer(
            $transporterObject,
            $this->settings,
            DotEnv::class
        );
    }
}