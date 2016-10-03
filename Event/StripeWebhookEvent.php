<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Stripe\Event as StripeEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookEvent extends Event
{
    /** @var \Stripe\Event */
    private $stripeEvent;

    /** @var \Symfony\Component\HttpFoundation\Response */
    private $response;

    /**
     * StripeEvent constructor.
     *
     * @param \Stripe\Event $stripeEvent
     */
    public function __construct(StripeEvent $stripeEvent)
    {
        $this->stripeEvent = $stripeEvent;
    }

    /**
     * @return \Stripe\Event
     */
    public function getStripeEvent()
    {
        return $this->stripeEvent;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
