<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;
use WarbleMedia\PhoenixBundle\Billing\PlanInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /** @var string */
    private $subscriptionClass;

    /**
     * SubscriptionManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                   $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface $paymentProcessor
     * @param string                                                       $subscriptionClass
     */
    public function __construct(ObjectManager $manager, PaymentProcessorInterface $paymentProcessor, string $subscriptionClass)
    {
        $this->manager = $manager;
        $this->paymentProcessor = $paymentProcessor;
        $this->subscriptionClass = $subscriptionClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @param string                                             $stripeToken
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function subscribeCustomerToPlan(CustomerInterface $customer, PlanInterface $plan, string $stripeToken): SubscriptionInterface
    {
        return $this->transactional(function () use ($customer, $plan, $stripeToken) {
            $subscription = $this->createSubscription($customer, $plan);

            $this->paymentProcessor->createNewSubscription($customer, $subscription, $stripeToken);

            $this->updateSubscription($subscription);

            return $subscription;
        });
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface   $plan
     * @return \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface
     */
    public function switchCustomerToPlan(CustomerInterface $customer, PlanInterface $plan): SubscriptionInterface
    {
        if (!$customer->hasSubscription()) {
            throw new \InvalidArgumentException('Can not switch plan for customer that is not subscribed.');
        }

        return $this->transactional(function () use ($customer, $plan) {
            $subscription = $customer->getSubscription();
            $subscription->setStripePlan($plan->getId());
            $subscription->setEndsAt(null);

            $this->paymentProcessor->changeSubscriptionPlan($customer, $subscription);

            $this->updateSubscription($subscription);

            return $subscription;
        });
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

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function transactional(callable $callback)
    {
        if ($this->manager instanceof EntityManager) {
            return $this->manager->transactional($callback);
        } else {
            return $callback();
        }
    }
}
