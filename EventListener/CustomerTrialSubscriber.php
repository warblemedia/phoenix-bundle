<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;

class CustomerTrialSubscriber implements EventSubscriberInterface
{
    /** @var int */
    private $trialDays;

    /**
     * CustomerTrialSubscriber constructor.
     *
     * @param int $trialDays
     */
    public function __construct(int $trialDays)
    {
        $this->trialDays = $trialDays;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PhoenixEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var \WarbleMedia\PhoenixBundle\Model\UserInterface $user */
        $user = $event->getForm()->getData();
        $customer = $user->getCustomer();

        $trialEndsAt = new \DateTime();
        $trialEndsAt->modify($this->trialDays . ' day');

        $customer->setTrialEndsAt($trialEndsAt);
    }
}
