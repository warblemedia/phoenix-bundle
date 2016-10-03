<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Gedmo\Timestampable\Traits\Timestampable;

abstract class Invoice implements InvoiceInterface
{
    use Timestampable;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $stripeId;

    /** @var string */
    protected $taxAmount;

    /** @var string */
    protected $totalAmount;

    /** @var \WarbleMedia\PhoenixBundle\Model\CustomerInterface */
    protected $customer;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStripeId()
    {
        return $this->stripeId;
    }

    /**
     * @param string $stripeId
     */
    public function setStripeId(string $stripeId)
    {
        $this->stripeId = $stripeId;
    }

    /**
     * @return string
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * @param string $taxAmount
     */
    public function setTaxAmount(string $taxAmount)
    {
        $this->taxAmount = $taxAmount;
    }

    /**
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param string $totalAmount
     */
    public function setTotalAmount(string $totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @internal
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer = null)
    {
        $this->customer = $customer;
    }
}
