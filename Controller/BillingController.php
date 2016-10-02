<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
use WarbleMedia\PhoenixBundle\Event\SubscriptionRequestEvent;
use WarbleMedia\PhoenixBundle\Event\SubscriptionResponseEvent;
use WarbleMedia\PhoenixBundle\Model\CustomerInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

class BillingController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscriptionAction(Request $request)
    {
        $user = $this->getUserOrError();
        $customer = $user->getCustomer();

        $activeSubscription = null;
        if ($customer->hasSubscription()) {
            $activeSubscription = $customer->getSubscription();
        }

        if ($activeSubscription === null || $activeSubscription->isOnGracePeriod()) {
            return $this->newSubscriptionAction($request, $customer, $activeSubscription);
        } else {
            return $this->updateSubscriptionAction($request, $customer, $activeSubscription);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request              $request
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function newSubscriptionAction(Request $request, CustomerInterface $customer, SubscriptionInterface $subscription = null)
    {
        $dispatcher = $this->get('event_dispatcher');
        $formFactory = $this->get('warble_media_phoenix.form.subscription_factory');
        $planManager = $this->get('warble_media_phoenix.billing.plan_manager');
        $subscriptionManager = $this->get('warble_media_phoenix.model.subscription_manager');

        if ($subscription) {
            $event = new SubscriptionRequestEvent($subscription, $request);
            $dispatcher->dispatch(PhoenixEvents::NEW_SUBSCRIPTION_INITIALIZE, $event);

            if ($event->getResponse() !== null) {
                return $event->getResponse();
            }
        }

        $form = $formFactory->createForm();

        $activePlan = null;
        if ($subscription) {
            $activePlan = $planManager->getPlan($subscription->getStripePlan());
        }

        if ($form->handleRequest($request)->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::NEW_SUBSCRIPTION_SUCCESS, $event);

            $data = $form->getData();
            $subscription = $subscriptionManager->subscribeCustomerToPlan($customer, $data['plan'], $data['stripeToken']);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_settings_subscription');
            }

            $event = new SubscriptionResponseEvent($subscription, $request, $response);
            $dispatcher->dispatch(PhoenixEvents::NEW_SUBSCRIPTION_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Settings:subscription.html.twig', [
            'user'         => $customer,
            'activePlan'   => $activePlan,
            'subscription' => $subscription,
            'form'         => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request              $request
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface     $customer
     * @param \WarbleMedia\PhoenixBundle\Model\SubscriptionInterface $subscription
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function updateSubscriptionAction(Request $request, CustomerInterface $customer, SubscriptionInterface $subscription = null)
    {
        // TODO: Implement updateSubscriptionAction() method.
        ]);
    }
}
