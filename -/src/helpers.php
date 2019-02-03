<?php

/**
 * @param $handler
 *
 * @return \std\mailer\Mailer
 */
function mailer($handler)
{
    return (new \std\mailer\Main($handler))->get();
}

function mailerc()
{
    return appc('\std\mailer~');
}
