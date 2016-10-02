<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\HttpFoundation\Response;

class SubscriptionRequestEvent extends SubscriptionEvent
{
    /** @var \Symfony\Component\HttpFoundation\Response */
    private $response;

    /**
     * @return \Symfony\Component\HttpFoundation\Response|null
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
