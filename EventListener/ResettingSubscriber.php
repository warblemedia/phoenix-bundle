<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use WarbleMedia\PhoenixBundle\Event\UserEvents;

class ResettingSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess',
        ];
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
