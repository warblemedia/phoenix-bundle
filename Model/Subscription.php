<?php

namespace WarbleMedia\PhoenixBundle\Model;

abstract class Subscription implements SubscriptionInterface
{
    /** @var mixed */
    protected $id;

    /** @var CustomerInterface */
    protected $customer;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @internal
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null $customer
     */
    public function setCustomer(CustomerInterface $customer = null)
    {
        $this->customer = $customer;
    }
}
