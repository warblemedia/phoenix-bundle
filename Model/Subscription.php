<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Gedmo\Timestampable\Traits\Timestampable;

abstract class Subscription implements SubscriptionInterface
{
    use Timestampable;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $stripeId;

    /** @var string */
    protected $stripePlan;

    /** @var \DateTime|null */
    protected $trialEndsAt;

    /** @var \DateTime|null */
    protected $endsAt;

    /** @var CustomerInterface */
    protected $customer;

    /**
     * Subscription constructor.
     */
    public function __construct()
    {
        $this->name = self::DEFAULT_SUBSCRIPTION;
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
    public function getStripePlan()
    {
        return $this->stripePlan;
    }

    /**
     * @param string $stripePlan
     */
    public function setStripePlan(string $stripePlan)
    {
        $this->stripePlan = $stripePlan;
    }

    /**
     * @return \DateTime|null
     */
    public function getTrialEndsAt()
    {
        return $this->trialEndsAt;
    }

    /**
     * @param \DateTime|null $trialEndsAt
     */
    public function setTrialEndsAt(\DateTime $trialEndsAt = null)
    {
        $this->trialEndsAt = $trialEndsAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTime|null $endsAt
     */
    public function setEndsAt(\DateTime $endsAt = null)
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return bool
     */
    public function isOnTrialPeriod(): bool
    {
        return $this->getTrialEndsAt() instanceof \DateTime &&
               $this->getTrialEndsAt()->getTimestamp() > time();
    }

    /**
     * @return bool
     */
    public function isOnGracePeriod(): bool
    {
        return $this->getEndsAt() instanceof \DateTime &&
               $this->getEndsAt()->getTimestamp() > time();
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
