<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Model\CustomerInterface;

class CustomerEvent extends Event
{
    /** @var \WarbleMedia\PhoenixBundle\Model\CustomerInterface */
    protected $customer;

    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;

    /**
     * CustomerEvent constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \Symfony\Component\HttpFoundation\Request          $request
     */
    public function __construct(CustomerInterface $customer, Request $request)
    {
        $this->customer = $customer;
        $this->request = $request;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface
     */
    public function getCustomer(): CustomerInterface
    {
        return $this->customer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
