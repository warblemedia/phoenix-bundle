<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

class SubscriptionResponseEvent extends SubscriptionEvent
{
    /** @var \Symfony\Component\HttpFoundation\Response */
    private $response;

    /**
     * SubscriptionResponseEvent constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param \Symfony\Component\HttpFoundation\Request              $request
     * @param \Symfony\Component\HttpFoundation\Response             $response
     */
    public function __construct(SubscriptionInterface $subscription, Request $request, Response $response)
    {
        parent::__construct($subscription, $request);
        $this->response = $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
