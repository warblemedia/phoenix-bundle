<?php

namespace WarbleMedia\PhoenixBundle\Mailer;

interface MailerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Mailer\MailInterface $mail
     */
    public function send(MailInterface $mail);
}
