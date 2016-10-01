<?php

namespace WarbleMedia\PhoenixBundle\Mailer\Mail;

use WarbleMedia\PhoenixBundle\Mailer\Mail;
use WarbleMedia\PhoenixBundle\Model\UserInterface;

class ResettingMail extends Mail
{
    /** @var \WarbleMedia\PhoenixBundle\Model\UserInterface */
    private $user;

    /**
     * ResettingMail constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Build the mail message.
     */
    public function build()
    {
        $user = $this->user;
        $confirmationUrl = $this->generateUrl('warble_media_phoenix_resetting_reset', [
            'token' => $user->getConfirmationToken(),
        ]);

        $this
            ->to($user->getName(), $user->getEmail())
            ->subject('Reset Password')
            ->render('WarbleMediaPhoenixBundle:Resetting:email.html.twig', [
                'user'            => $user,
                'confirmationUrl' => $confirmationUrl,
            ]);
    }
}
