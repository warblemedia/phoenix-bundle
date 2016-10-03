<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface CustomerManagerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $stripeToken
     */
    public function changePaymentMethod(CustomerInterface $customer, string $stripeToken);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function updateCustomer(CustomerInterface $customer);

    /**
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null
     */
    public function findCustomerByStripeId(string $stripeId);
}
