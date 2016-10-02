<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface CustomerInterface extends UserInterface
{
    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface[]
     */
    public function getSubscriptions(): array;

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface|null
     */
    public function getSubscription(string $name = 'default');

    /**
     * @return string[]
     */
    public function getSubscriptionNames(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function hasSubscription(string $name): bool;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function removeSubscription(SubscriptionInterface $subscription);
}
