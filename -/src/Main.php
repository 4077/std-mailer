<?php namespace std\mailer;

class Main
{
    private $mailer;

    /**
     * Main constructor.
     *
     * @param $handler
     *
     * @return \std\mailer\Mailer
     */
    public function __construct($handler)
    {
        $config = handlers()->render('std/mailer:', [
            'env' => app()->getEnv()
        ]);

        ra($config, handlers()->render($handler));

        $mailer = new \std\mailer\Mailer();

        if ($stub = $config['stub']) {
            $mailer->setStub($stub);
            $mailer->setStubWritePath(mailerc()->_protected('emails'));
        }

        $mailer->CharSet = "utf-8";
        $mailer->IsHTML();

        $senderData = $config['sender'];

        $mailer->isSMTP();
        $mailer->SMTPAuth = true;

        \ewma\Data\Data::extract($mailer, $senderData, '
            Host        host,
            Port        port,
            Username    user,
            Password    pass,
            SMTPSecure  smtp_secure,
            From        user,
            FromName    from_name
        ');

        if ($config['debug']) {
            $mailer->setDebug(true);
            $mailer->SMTPDebug = 2;
        }

        $mailer->setQueueInstance($config['queue']);

        if ($config['env_append']) {
            $mailer->FromName .= ' (' . app()->getEnv() . ')';
        }

        $mailer->IsHTML();

        if ($bccRecipients = l2a($config['bcc_recipients'])) {
            foreach ($bccRecipients as $recipient) {
                $mailer->addBCC($recipient);
            }
        }

        $this->mailer = $mailer;
    }

    public function get()
    {
        return $this->mailer;
    }
}
