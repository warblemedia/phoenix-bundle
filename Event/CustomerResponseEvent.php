<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarbleMedia\PhoenixBundle\Model\CustomerInterface;

class CustomerResponseEvent extends CustomerEvent
{
    /** @var \Symfony\Component\HttpFoundation\Response */
    private $response;

    /**
     * CustomerResponseEvent constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \Symfony\Component\HttpFoundation\Request          $request
     * @param \Symfony\Component\HttpFoundation\Response         $response
     */
    public function __construct(CustomerInterface $customer, Request $request, Response $response)
    {
        parent::__construct($customer, $request);
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
