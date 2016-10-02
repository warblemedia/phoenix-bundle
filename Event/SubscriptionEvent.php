<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

class SubscriptionEvent extends Event
{
    /** @var \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface */
    protected $subscription;

    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;

    /**
     * SubscriptionEvent constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param \Symfony\Component\HttpFoundation\Request              $request
     */
    public function __construct(SubscriptionInterface $subscription, Request $request)
    {
        $this->subscription = $subscription;
        $this->request = $request;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
