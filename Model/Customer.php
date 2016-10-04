<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Customer extends User implements CustomerInterface
{
    /** @var string */
    protected $stripeId;

    /** @var string */
    protected $cardBrand;

    /** @var string */
    protected $cardLastFour;

    /** @var \DateTime|null */
    protected $trialEndsAt;

    /** @var \Doctrine\Common\Collections\Collection */
    protected $subscriptions;

    /** @var \Doctrine\Common\Collections\Collection */
    protected $invoices;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subscriptions = new ArrayCollection();
        $this->invoices = new ArrayCollection();
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
    public function getCardBrand()
    {
        return $this->cardBrand;
    }

    /**
     * @param string $cardBrand
     */
    public function setCardBrand($cardBrand)
    {
        $this->cardBrand = $cardBrand;
    }

    /**
     * @return string
     */
    public function getCardLastFour()
    {
        return $this->cardLastFour;
    }

    /**
     * @param string $cardLastFour
     */
    public function setCardLastFour($cardLastFour)
    {
        $this->cardLastFour = $cardLastFour;
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
     * @return bool
     */
    public function isOnTrialPeriod(): bool
    {
        return $this->getTrialEndsAt() instanceof \DateTime &&
               $this->getTrialEndsAt()->getTimestamp() > time();
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface
     */
    public function getCustomer()
    {
        return $this;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface[]
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions->toArray();
    }

    /**
     * @param string $name
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface|null
     */
    public function getSubscription(string $name = SubscriptionInterface::DEFAULT_SUBSCRIPTION)
    {
        foreach ($this->getSubscriptions() as $subscription) {
            if ($subscription->getName() === $name) {
                return $subscription;
            }
        }

        return null;
    }

    /**
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface|null
     */
    public function getSubscriptionByStripeId(string $stripeId)
    {
        foreach ($this->getSubscriptions() as $subscription) {
            if ($subscription->getStripeId() === $stripeId) {
                return $subscription;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getSubscriptionNames(): array
    {
        $names = [];

        foreach ($this->getSubscriptions() as $subscription) {
            $names[] = $subscription->getName();
        }

        return $names;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasSubscription(string $name = SubscriptionInterface::DEFAULT_SUBSCRIPTION): bool
    {
        return in_array($name, $this->getSubscriptionNames(), true);
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription)
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setCustomer($this);
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     */
    public function removeSubscription(SubscriptionInterface $subscription)
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            $subscription->setCustomer(null);
        }
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface[]
     */
    public function getInvoices(): array
    {
        return $this->invoices->toArray();
    }

    /**
     * @param mixed $id
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface|null
     */
    public function getInvoice($id)
    {
        foreach ($this->getInvoices() as $invoice) {
            if ($invoice->getId() === $id) {
                return $invoice;
            }
        }

        return null;
    }

    /**
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface|null
     */
    public function getInvoiceByStripeId(string $stripeId)
    {
        foreach ($this->getInvoices() as $invoice) {
            if ($invoice->getStripeId() === $stripeId) {
                return $invoice;
            }
        }

        return null;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     */
    public function addInvoice(InvoiceInterface $invoice)
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setCustomer($this);
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     */
    public function removeInvoice(InvoiceInterface $invoice)
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            $invoice->setCustomer(null);
        }
    }
}
