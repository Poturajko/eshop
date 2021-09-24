<?php

namespace App\Core\Mailer;

use PHPMailer\PHPMailer\PHPMailer;

class MailerFacade
{
    private object $mailer;


    public function __construct(?array $settings = null)
    {
        $this->mailer =  (new MailerFactory($settings))->create(PHPMailer::class);
    }

    public function basicMail(string $subject, string $from, string $to, string $message)
    {
        return $this->mailer
            ->subject($subject)
            ->from($from)
            ->address($to)
            ->body($message)
            ->send();
    }

}