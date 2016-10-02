<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Customer extends User implements CustomerInterface
{
    /** @var \Doctrine\Common\Collections\Collection */
    protected $subscriptions;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subscriptions = new ArrayCollection();
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface
     */
    public function getCustomer()
    {
        return $this;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface[]
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions->toArray();
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface|null
     */
    public function getSubscription(string $name = 'default')
    {
        foreach ($this->getSubscriptions() as $subscription) {
            if ($subscription->getName() === $name) {
                return $subscription;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getSubscriptionNames(): array
    {
        $names = [];

        foreach ($this->getSubscriptions() as $subscription) {
            $names[] = $subscription->getName();
        }

        return $names;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasSubscription(string $name): bool
    {
        return in_array($name, $this->getSubscriptionNames(), true);
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription)
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setCustomer($this);
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function removeSubscription(SubscriptionInterface $subscription)
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            $subscription->setCustomer(null);
        }
    }
}
