<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface InvoiceInterface extends ModelInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface
     */
    public function getCustomer();

    /**
     * @internal
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer = null);
}
