<?php

namespace WarbleMedia\PhoenixBundle\Model;

use WarbleMedia\PhoenixBundle\Billing\PlanInterface;

interface SubscriptionManagerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface   $user
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface $plan
     * @param string                                           $token
     * @param bool                                             $fromRegistration
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function createSubscription(UserInterface $user, PlanInterface $plan, string $token, bool $fromRegistration = false): SubscriptionInterface;
}
