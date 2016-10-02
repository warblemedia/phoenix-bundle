<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface SubscriptionInterface extends ModelInterface
{
    const DEFAULT_SUBSCRIPTION = 'default';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName(string $name);

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
    public function getStripePlan();

    /**
     * @param string $stripePlan
     */
    public function setStripePlan(string $stripePlan);

    /**
     * @return \DateTime|null
     */
    public function getTrialEndsAt();

    /**
     * @param \DateTime|null $trialEndsAt
     */
    public function setTrialEndsAt(\DateTime $trialEndsAt = null);

    /**
     * @return \DateTime|null
     */
    public function getEndsAt();

    /**
     * @param \DateTime|null $endsAt
     */
    public function setEndsAt(\DateTime $endsAt = null);

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null;
     */
    public function getCustomer();

    /**
     * @internal
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null $customer
     */
    public function setCustomer(CustomerInterface $customer = null);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
