<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use WarbleMedia\PhoenixBundle\Billing\PlanInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /** @var string */
    private $subscriptionClass;

    /**
     * SubscriptionManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param string                                     $subscriptionClass
     */
    public function __construct(ObjectManager $manager, string $subscriptionClass)
    {
        $this->manager = $manager;
        $this->subscriptionClass = $subscriptionClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @param bool                                               $fromRegistration
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function createSubscription(CustomerInterface $customer, PlanInterface $plan, bool $fromRegistration = false): SubscriptionInterface
    {
        $subscription = $this->createSubscriptionInstance();
        $subscription->setStripePlan($plan->getId());

        // Set trial end date on the subscription. By default, we skip the trial for
        // non-registration requests as the customer has usually already trialed by this point.
        $trialDays = $plan->getTrialDays();
        if ($fromRegistration && $trialDays > 0) {
            $trialEndsAt = new \DateTime();
            $trialEndsAt->modify((int) $trialDays . ' day');
            $subscription->setTrialEndsAt($trialEndsAt);
        }

        $customer->addSubscription($subscription);

        return $subscription;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param bool                                                   $flush
     */
    public function updateSubscription(SubscriptionInterface $subscription, bool $flush = true)
    {
        $this->manager->persist($subscription);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    protected function createSubscriptionInstance(): SubscriptionInterface
    {
        return new $this->subscriptionClass;
    }
}
