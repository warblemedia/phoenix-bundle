<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
use WarbleMedia\PhoenixBundle\Event\UserResponseEvent;

class SyncStripeCustomerSubscriber implements EventSubscriberInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /**
     * SyncStripeCustomerSubscriber constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface $paymentProcessor
     */
    public function __construct(PaymentProcessorInterface $paymentProcessor)
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PhoenixEvents::PROFILE_EDIT_COMPLETED => 'onProfileEditComplete',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\UserResponseEvent $event
     */
    public function onProfileEditComplete(UserResponseEvent $event)
    {
        $user = $event->getUser();
        $customer = $user->getCustomer();

        $this->paymentProcessor->synchroniseCustomer($customer);
    }
}
