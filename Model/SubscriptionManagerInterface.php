<?php

namespace WarbleMedia\PhoenixBundle\Model;

use WarbleMedia\PhoenixBundle\Billing\PlanInterface;

interface SubscriptionManagerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @param bool                                               $fromRegistration
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function createSubscription(CustomerInterface $customer, PlanInterface $plan, bool $fromRegistration = false): SubscriptionInterface;
}
