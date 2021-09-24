<?php

namespace App\Core\Mailer;

interface IMailer
{
    public function subject(string $subject) : self;

    public function body(string $message) : self;

    public function from(string $from, ?string $name = null) : self;

    public function address($args = null) : self;

    public function toReply(string $from, ?string $name = null) : self;

    public function cc(string $cc) : self;

    public function bcc(string $bcc) : self;

    public function attachments($args = null) : self;

    public function send(?string $successMsg = null, bool $saveMail = false);
}