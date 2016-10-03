<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface InvoiceInterface extends ModelInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getStripeId();

    /**
     * @param string $stripeId
     */
    public function setStripeId(string $stripeId);

    /**
     * @return string
     */
    public function getTaxAmount();

    /**
     * @param string $taxAmount
     */
    public function setTaxAmount(string $taxAmount);

    /**
     * @return string
     */
    public function getTotalAmount();

    /**
     * @param string $totalAmount
     */
    public function setTotalAmount(string $totalAmount);

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
