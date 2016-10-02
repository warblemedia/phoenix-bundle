<?php

namespace WarbleMedia\PhoenixBundle\Billing;

use Stripe\Customer as StripeCustomer;
use Stripe\Token as StripeToken;
use WarbleMedia\PhoenixBundle\Model\CustomerInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

class PaymentProcessor implements PaymentProcessorInterface
{
    /** @var string */
    private $stripeKey;

    /** @var bool */
    private $prorate = true;

    /**
     * PaymentProcessor constructor.
     *
     * @param string $stripeKey
     */
    public function __construct(string $stripeKey)
    {
        $this->stripeKey = $stripeKey;
        // TODO: Add configuration param for proration
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param string|null                                            $token
     * @param array                                                  $options
     */
    public function createNewSubscription(CustomerInterface $customer, SubscriptionInterface $subscription, string $token = null, array $options = [])
    {
        $stripeCustomer = $this->getStripeCustomer($customer, $token, $options);
        $stripeSubscription = $stripeCustomer->subscriptions->create(
            $this->buildPayload($subscription)
        );

        $subscription->setStripeId($stripeSubscription->id);
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param array                                                  $options
     */
    public function changeSubscriptionPlan(CustomerInterface $customer, SubscriptionInterface $subscription, array $options = [])
    {
        if (!$customer->hasSubscription()) {
            throw new \InvalidArgumentException('Can not switch plan for customer that is not subscribed.');
        }
        if (!$subscription->getStripeId()) {
            throw new \InvalidArgumentException('Can not switch plan for subscription that has no stripe id.');
        }

        $stripeCustomer = $this->getStripeCustomer($customer, null, $options);
        $stripeSubscription = $stripeCustomer->subscriptions->retrieve($subscription->getStripeId());
        $stripeSubscription->plan = $subscription->getStripePlan();
        $stripeSubscription->prorate = $this->prorate;

        // If no specific trial end date has been set, the default behavior should be
        // to maintain the current trial state, whether that is "active" or to run
        // the swap out with the exact number of days left on this current plan.
        if ($subscription->isOnTrialPeriod()) {
            $stripeSubscription->trial_end = $subscription->getTrialEndsAt()->getTimestamp();
        } else {
            $stripeSubscription->trial_end = 'now';
        }

        $stripeSubscription->save();
    }

    /**
     * Get the Stripe customer instance for the current user and token.
     *
     * @param  string|null $token
     * @param  array       $options
     * @return \Stripe\Customer
     */
    protected function getStripeCustomer(CustomerInterface $customer, string $token = null, array $options = [])
    {
        if ($customer->getStripeId()) {
            $stripeCustomer = StripeCustomer::retrieve($customer->getStripeId(), $this->stripeKey);
        } else {
            $stripeCustomer = $this->createStripeCustomer($customer, $options);
        }

        if ($token) {
            $this->updateStripeCustomerCard($stripeCustomer, $token);
        }

        return $stripeCustomer;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param array                                              $options
     * @return \Stripe\Customer
     */
    protected function createStripeCustomer(CustomerInterface $customer, array $options = []): StripeCustomer
    {
        if (!array_key_exists('email', $options)) {
            $options['email'] = $customer->getEmail();
        }

        // Here we will create the customer instance on Stripe and store the ID of the
        // user from Stripe. This ID will correspond with the Stripe user instances
        // and allow us to retrieve users from Stripe later when we need to work.
        $stripeCustomer = StripeCustomer::create($options, $this->stripeKey);

        $customer->setStripeId($stripeCustomer->id);

        return $stripeCustomer;
    }

    /**
     * @param \Stripe\Customer $stripeCustomer
     * @param string           $token
     */
    protected function updateStripeCustomerCard(StripeCustomer $stripeCustomer, string $token)
    {
        $stripeToken = StripeToken::retrieve($token, ['api_key' => $this->stripeKey]);

        // If the given token already has the card as their default source, we can just
        // bail out of the method now. We don't need to keep adding the same card to
        // the user's account each time we go through this particular method call.
        if ($stripeToken->card->id === $stripeCustomer->default_source) {
            return;
        }

        $card = $stripeCustomer->sources->create(['source' => $stripeToken]);
        $stripeCustomer->default_source = $card->id;
        $stripeCustomer->save();
    }

    /**
     * Build the payload for subscription creation.
     *
     * @return array
     */
    protected function buildPayload(SubscriptionInterface $subscription)
    {
        return array_filter([
            'plan'        => $subscription->getStripePlan(),
            'trial_end'   => $subscription->getTrialEndsAt(),
        ]);
    }
}
