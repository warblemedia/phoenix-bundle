<?php

namespace WarbleMedia\PhoenixBundle\Billing;

use Stripe\Customer as StripeCustomer;
use Stripe\Error\InvalidRequest;
use Stripe\Invoice as StripeInvoice;
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
     * @param bool   $prorate
     */
    public function __construct(string $stripeKey, bool $prorate = true)
    {
        $this->stripeKey = $stripeKey;
        $this->prorate = $prorate;
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
            $this->buildSubscriptionPayload($subscription)
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

        /** @var \Stripe\Subscription $stripeSubscription */
        $stripeSubscription = $stripeCustomer->subscriptions->retrieve($subscription->getStripeId());
        $stripeSubscription->plan = $subscription->getStripePlan();
        $stripeSubscription->prorate = $this->prorate;
        $stripeSubscription->trial_end = $this->determineTrialEndDate($subscription);

        $stripeSubscription->save();
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @param array                                                  $options
     */
    public function cancelSubscription(CustomerInterface $customer, SubscriptionInterface $subscription, array $options = [])
    {
        if (!$customer->hasSubscription() || !$subscription->getStripeId()) {
            return;
        }

        $stripeCustomer = $this->getStripeCustomer($customer, null, $options);

        /** @var \Stripe\Subscription $stripeSubscription */
        $stripeSubscription = $stripeCustomer->subscriptions->retrieve($subscription->getStripeId());
        $stripeSubscription->cancel(['at_period_end' => true]);

        // If the user was on trial, we will set the grace period to end when the trial
        // would have ended. Otherwise, we'll retrieve the end of the billing period
        // period and make that the end of the grace period for this current user.
        if ($subscription->isOnTrialPeriod()) {
            $subscription->setEndsAt($subscription->getTrialEndsAt());
        } else {
            $endsAt = new \DateTime();
            $endsAt->setTimestamp($stripeSubscription->current_period_end);
            $subscription->setEndsAt($endsAt);
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function invoiceCustomer(CustomerInterface $customer)
    {
        if ($customer->getStripeId()) {
            try {
                $invoice = StripeInvoice::create(['customer' => $customer->getStripeId()], $this->stripeKey);
                $invoice->pay();
            } catch (InvalidRequest $e) {
                // Do nothing...
            }
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $token
     * @param array                                              $options
     */
    public function updatePaymentMethod(CustomerInterface $customer, string $token, array $options = [])
    {
        if ($customer->getStripeId()) {
            $stripeCustomer = StripeCustomer::retrieve($customer->getStripeId(), $this->stripeKey);
        } else {
            $stripeCustomer = $this->createStripeCustomer($customer, $options);
        }

        $this->updateStripeCustomerCard($customer, $stripeCustomer, $token);
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $invoiceId
     * @return \Stripe\Invoice|null
     */
    public function getCustomerInvoice(CustomerInterface $customer, string $invoiceId)
    {
        if ($customer->getStripeId()) {
            $stripeInvoice = StripeInvoice::retrieve($invoiceId, $this->stripeKey);

            if ($stripeInvoice->customer === $customer->getStripeId()) {
                return $stripeInvoice;
            }
        }

        return null;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     */
    public function synchroniseCustomer(CustomerInterface $customer)
    {
        if ($customer->getStripeId()) {
            $stripeCustomer = StripeCustomer::retrieve($customer->getStripeId(), $this->stripeKey);
            $stripeCustomer->email = $customer->getEmail();
            $stripeCustomer->save();
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param  string|null                                       $token
     * @param  array                                             $options
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
            $this->updateStripeCustomerCard($customer, $stripeCustomer, $token);
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
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param \Stripe\Customer                                   $stripeCustomer
     * @param string                                             $token
     */
    protected function updateStripeCustomerCard(CustomerInterface $customer, StripeCustomer $stripeCustomer, string $token)
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

        // Update the last four digits and the card brand on the customer, which
        // is convenient when displaying on the front-end when updating the cards.
        $customer->setCardBrand($card->brand);
        $customer->setCardLastFour($card->last4);
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @return int
     */
    protected function determineTrialEndDate(SubscriptionInterface $subscription):int
    {
        // If no specific trial end date has been set, the default behavior should be
        // to maintain the current trial state, whether that is "active" or to run
        // the swap out with the exact number of days left on this current plan.
        if ($subscription->isOnTrialPeriod()) {
            return $subscription->getTrialEndsAt()->getTimestamp();
        }

        return 'now';
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @return array
     */
    protected function buildSubscriptionPayload(SubscriptionInterface $subscription)
    {
        return array_filter([
            'plan'      => $subscription->getStripePlan(),
            'trial_end' => $subscription->getTrialEndsAt(),
        ]);
    }
}
