<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface SubscriptionInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null;
     */
    public function getCustomer();

    /**
     * @internal
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null $customer
     */
    public function setCustomer(CustomerInterface $customer = null);
}
