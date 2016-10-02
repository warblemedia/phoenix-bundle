<?php

namespace WarbleMedia\PhoenixBundle\Model;

use WarbleMedia\PhoenixBundle\Billing\PlanInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /** @var string */
    private $subscriptionClass;

    /**
     * SubscriptionManager constructor.
     *
     * @param string $subscriptionClass
     */
    public function __construct(string $subscriptionClass)
    {
        $this->subscriptionClass = $subscriptionClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @param bool                                               $fromRegistration
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function createSubscription(CustomerInterface $customer, PlanInterface $plan, bool $fromRegistration = false): SubscriptionInterface
    {
        // TODO: Implement subscribe() method.
    }
}