<?php namespace std\mailer;

class Mailer extends \PHPMailer\PHPMailer\PHPMailer
{
    private $stub = false;

    public function setStub($enabled)
    {
        $this->stub = $enabled;
    }

    private $stubWritePath;

    public function setStubWritePath($path)
    {
        $this->stubWritePath = $path;
    }

    private $debug = false;

    public function setDebug($enabled)
    {
        $this->debug = $enabled;
    }

    private $queueInstance;

    public function setQueueInstance($instance = 'mail')
    {
        $this->queueInstance = $instance;
    }

    public function send()
    {
        if ($this->stub) {
            $filePath = '/' . path($this->stubWritePath, time() . rand(1111, 9999)) . '.html';

            write($filePath, $this->Body);

            appc()->console('mailer stub enabled. write to file: ' . $filePath);
        } else {
            if (!parent::send()) {
                appc()->console($this->ErrorInfo);
            }
        }
    }

    public function queue($instance = 'mail')
    {
        $instance or $instance = $this->queueInstance;

        $call = appc()->_abs('\std\mailer~:sendSerialized', [
            'serialized' => serialize($this)
        ]);

        queue($instance)->call($call)->ttl(300)->sync();
    }
}
