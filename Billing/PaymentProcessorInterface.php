<?php

namespace WarbleMedia\PhoenixBundle\Billing;

use WarbleMedia\PhoenixBundle\Model\CustomerInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

interface PaymentProcessorInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param string|null                                            $token
     */
    public function process(CustomerInterface $customer, SubscriptionInterface $subscription, string $token = null);
}
