<?php namespace std\mailer\controllers;

class Main extends \Controller
{
    public function send()
    {
        $mailer = mailer('mailers:dev');

        $subject = $this->data('subject');
        $body = $this->data('body');

        if (is_array($body)) {
            $body = implode("<br>", $body);
        }

        $recipients = l2a($this->data('recipients'));

        foreach ($recipients as $recipient) {
            $mailer->addAddress($recipient);
        }

        $mailer->Subject = $subject;
        $mailer->Body = $body;

        $mailer->send();
    }

    public function sendSerialized()
    {
        if ($mailer = unserialize($this->data('serialized'))) {
            $mailer->send();
        }
    }
}
