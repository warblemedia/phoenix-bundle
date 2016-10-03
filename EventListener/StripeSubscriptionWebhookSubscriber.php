<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WarbleMedia\PhoenixBundle\Event\StripeEvents;
use WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent;
use WarbleMedia\PhoenixBundle\Model\CustomerManagerInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionManagerInterface;

class StripeSubscriptionWebhookSubscriber implements EventSubscriberInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Model\CustomerManagerInterface */
    private $customerManager;

    /** @var \WarbleMedia\PhoenixBundle\Model\SubscriptionManagerInterface */
    private $subscriptionManager;

    /**
     * StripeSubscriptionWebhookSubscriber constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerManagerInterface     $customerManager
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionManagerInterface $subscriptionManager
     */
    public function __construct(CustomerManagerInterface $customerManager, SubscriptionManagerInterface $subscriptionManager)
    {
        $this->customerManager = $customerManager;
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            StripeEvents::CUSTOMER_SUBSCRIPTION_DELETED => 'onCustomerSubscriptionDeleted',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent $event
     */
    public function onCustomerSubscriptionDeleted(StripeWebhookEvent $event)
    {
        $stripeEvent = $event->getStripeEvent();
        $stripeCustomerId = $stripeEvent->data->object->customer;
        $stripeSubscriptionId = $stripeEvent->data->object->id;

        $customer = $this->customerManager->findCustomerByStripeId($stripeCustomerId);

        if ($customer) {
            $subscription = $customer->getSubscriptionByStripeId($stripeSubscriptionId);

            if ($subscription) {
                $endedAt = new \DateTime();
                $endedAt->setTimestamp($stripeEvent->data->object->ended_at);
                $subscription->setEndsAt($endedAt);
                $this->subscriptionManager->updateSubscription($subscription);
            }
        }
    }
}
