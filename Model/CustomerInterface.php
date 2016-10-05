<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface CustomerInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getEmail();

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
    public function getCardBrand();

    /**
     * @param string $cardBrand
     */
    public function setCardBrand($cardBrand);

    /**
     * @return string
     */
    public function getCardLastFour();

    /**
     * @param string $cardLastFour
     */
    public function setCardLastFour($cardLastFour);

    /**
     * @return \DateTime|null
     */
    public function getTrialEndsAt();

    /**
     * @param \DateTime|null $trialEndsAt
     */
    public function setTrialEndsAt(\DateTime $trialEndsAt = null);

    /**
     * @return bool
     */
    public function isOnTrialPeriod(): bool;

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface[]
     */
    public function getSubscriptions(): array;

    /**
     * @param string $name
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface|null
     */
    public function getSubscription(string $name = SubscriptionInterface::DEFAULT_SUBSCRIPTION);

    /**
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface|null
     */
    public function getSubscriptionByStripeId(string $stripeId);

    /**
     * @return string[]
     */
    public function getSubscriptionNames(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function hasSubscription(string $name = SubscriptionInterface::DEFAULT_SUBSCRIPTION): bool;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function removeSubscription(SubscriptionInterface $subscription);

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface[]
     */
    public function getInvoices(): array;

    /**
     * @param mixed $id
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface|null
     */
    public function getInvoice($id);

    /**
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface|null
     */
    public function getInvoiceByStripeId(string $stripeId);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     */
    public function addInvoice(InvoiceInterface $invoice);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     */
    public function removeInvoice(InvoiceInterface $invoice);
}
