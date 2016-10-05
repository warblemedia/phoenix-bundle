<?php

namespace WarbleMedia\PhoenixBundle\Billing;

use WarbleMedia\PhoenixBundle\Model\CustomerInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

interface PaymentProcessorInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param string|null                                            $token
     * @param array                                                  $options
     */
    public function createNewSubscription(CustomerInterface $customer, SubscriptionInterface $subscription, string $token = null, array $options = []);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param array                                                  $options
     */
    public function changeSubscriptionPlan(CustomerInterface $customer, SubscriptionInterface $subscription, array $options = []);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param array                                                  $options
     */
    public function cancelSubscription(CustomerInterface $customer, SubscriptionInterface $subscription, array $options = []);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function invoiceCustomer(CustomerInterface $customer);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $token
     * @param array                                              $options
     */
    public function updatePaymentMethod(CustomerInterface $customer, string $token, array $options = []);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $invoiceId
     * @return \Stripe\Invoice|null
     */
    public function getCustomerInvoice(CustomerInterface $customer, string $invoiceId);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function synchroniseCustomer(CustomerInterface $customer);
}
