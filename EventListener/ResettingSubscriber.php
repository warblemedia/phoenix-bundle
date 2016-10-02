<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
use WarbleMedia\PhoenixBundle\Event\UserRequestEvent;

class ResettingSubscriber implements EventSubscriberInterface
{
    /** @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
    private $urlGenerator;

    /** @var int */
    private $tokenTimeout;

    /**
     * ResettingSubscriber constructor.
     *
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param int                                                        $tokenTimeout
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, int $tokenTimeout)
    {
        $this->urlGenerator = $urlGenerator;
        $this->tokenTimeout = $tokenTimeout;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PhoenixEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            PhoenixEvents::RESETTING_RESET_SUCCESS    => 'onResettingResetSuccess',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\UserRequestEvent $event
     */
    public function onResettingResetInitialize(UserRequestEvent $event)
    {
        $user = $event->getUser();
        $request = $event->getRequest();

        if (!$user->isPasswordRequestNonExpired($this->tokenTimeout)) {
            if ($request->hasSession()) {
                $request->getSession()->set('warble_media_phoenix_resetting_error', 'The password reset token has expired.');
            }
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('warble_media_phoenix_resetting_request')));
        }
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function onResettingResetSuccess(FormEvent $event)
    {
        /** @var $user \WarbleMedia\PhoenixBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $user->setEnabled(true);
    }
}
