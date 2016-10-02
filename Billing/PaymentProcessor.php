<?php

namespace WarbleMedia\PhoenixBundle\Billing;

use WarbleMedia\PhoenixBundle\Model\CustomerInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

class PaymentProcessor implements PaymentProcessorInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param string|null                                            $token
     * @param array                                                  $options
     */
    public function process(CustomerInterface $customer, SubscriptionInterface $subscription, string $token = null, array $options = [])
    {
        // TODO: Implement process() method.
    }
}
