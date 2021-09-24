<?php

namespace App\Core\Mailer;

use DevCoder\DotEnv;
use InvalidArgumentException;
use PHPMailer\PHPMailer\Exception;

class Mailer implements IMailer
{
    private object $transporterObject;

    private array $options = [];

    private array $setting = [];

    public function __construct(object $transporterObject, ?array $settings = null, ?string $dotEnvString = null)
    {
        if ($dotEnvString !== null) {
            (new DotEnv(ENV))->load();
        }
        $this->transporterObject = $transporterObject;
        if (is_array($settings) && count($settings) > 0) {
            $this->setSettings($settings);
        } else {
            $this->setSettings(null);
        }

    }

    public function isValid($value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Invalid or empty argument provided. Please address this.');
        }
    }

    protected function getSettings(?array $settings = null): void
    {
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                if (isset($key) && $key !== '') {
                    $this->setting[$key] = ($value ?? '');
                }
            }
        }
    }

    protected function defaultOrEnv(string $param)
    {
        $this->isValid($param);
        if (isset($this->setting[$param]) && $this->setting[$param] !== '' && !isset($_ENV[$param])) {
            return $this->setting[$param];
        }
        if (isset($_ENV[$param]) && empty($this->setting[$param])) {
            return $_ENV[$param];
        }
    }

    protected function setSettings(?array $settings = null): void
    {
        $this->getSettings($settings);

        $this->transporterObject->isSMTP();
        $this->transporterObject->Host = $this->defaultOrEnv('MAIL_HOST');
        $this->transporterObject->SMTPAuth = $this->defaultOrEnv('MAIL_SMTP_AUTH');
        $this->transporterObject->Username = $this->defaultOrEnv('MAIL_USERNAME');
        $this->transporterObject->Password = $this->defaultOrEnv('MAIL_PASSWORD');
        $this->transporterObject->SMTPSecure = $this->defaultOrEnv('MAIL_ENCRYPTION');
        $this->transporterObject->Port = $this->defaultOrEnv('MAIL_PORT');
    }

    public function subject(string $subject): self
    {
        $this->isValid($subject);
        $this->transporterObject->isHTML(true);
        $this->transporterObject->Subject = $subject;

        return $this;
    }

    public function from(string $from, ?string $name = null): self
    {
        $this->isValid($from);
        $this->transporterObject->setFrom($from, $name);

        return $this;
    }

    public function body(string $message, $externalSource = null, $externalSourcePath = null): self
    {
        $this->isValid($message);
        $this->transporterObject->Body = $message;
        $this->transporterObject->AltBody = $message;
        if ($externalSource != null) {
            $this->transporterObject->msgHTML(file_get_contents($externalSource), $externalSourcePath);
        }

        return $this;
    }

    public function address($args = null): self
    {
        if (is_array($args) && $args != null) {
            foreach ($args as $name => $address) {
                $this->transporterObject->addAddress($address, $name);
            }
        } else {
            $this->transporterObject->addAddress($args);
        }

        return $this;
    }

    public function toReply(string $from, ?string $name = null): self
    {
        $this->isValid($from);
        $this->transporterObject->addReplyTo($from, $name);

        return $this;
    }

    public function cc(string $cc): self
    {
        $this->isValid($cc);
        $this->transporterObject->addCC($cc);

        return $this;
    }

    public function bcc(string $bcc): self
    {
        $this->isValid($bcc);
        $this->transporterObject->addBCC($bcc);

        return $this;
    }

    public function attachments($args = null): self
    {
        if (is_array($args)) {
            foreach ($args as $name => $attachment) {
                $this->transporterObject->addAttachment($attachment, $name);
            }
        } else {
            $this->transporterObject->addAttachment($args);
        }
        return $this;

    }

    public function send(?string $successMsg = null, bool $saveMail = false)
    {
        try {
            if (!$this->transporterObject->send()) {
                return 'Mail Error: ' . $this->transporterObject->ErrorInfo;
            }

            if ($successMsg != null) {
                if ($saveMail === true) {
                    $this->saveMail($this->transporterObject);
                }
                return $successMsg;
            }
        } catch (Exception $exception) {
            throw $exception;
        }
        return false;
    }

    private function saveMail(object $transporterObject): void
    {
        $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
        $imapStream = imap_open($path, $transporterObject->Username, $transporterObject->Password);
        $result = imap_append($imapStream, $path, $transporterObject->getSentMIMEMessage());
        imap_close($imapStream);
    }
}