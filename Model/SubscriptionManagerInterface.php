<?php

namespace WarbleMedia\PhoenixBundle\Model;

use WarbleMedia\PhoenixBundle\Billing\PlanInterface;

interface SubscriptionManagerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @param string                                             $stripeToken
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function subscribeCustomerToPlan(CustomerInterface $customer, PlanInterface $plan, string $stripeToken): SubscriptionInterface;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function switchCustomerToPlan(CustomerInterface $customer, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @param bool                                               $fromRegistration
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function createSubscription(CustomerInterface $customer, PlanInterface $plan, bool $fromRegistration = false): SubscriptionInterface;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function updateSubscription(SubscriptionInterface $subscription);
}
