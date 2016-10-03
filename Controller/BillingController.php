<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Event\CustomerRequestEvent;
use WarbleMedia\PhoenixBundle\Event\CustomerResponseEvent;
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

        if ($activeSubscription === null || $activeSubscription->isExpired()) {
            return $this->newSubscriptionAction($request, $customer, $activeSubscription);
        } else {
            return $this->updateSubscriptionAction($request, $customer, $activeSubscription);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelSubscriptionAction(Request $request)
    {
        $dispatcher = $this->get('event_dispatcher');
        $subscriptionManager = $this->get('warble_media_phoenix.model.subscription_manager');

        $user = $this->getUserOrError();
        $customer = $user->getCustomer();

        $activeSubscription = null;
        if ($customer->hasSubscription()) {
            $activeSubscription = $customer->getSubscription();
        }

        $event = new SubscriptionRequestEvent($activeSubscription, $request);
        $dispatcher->dispatch(PhoenixEvents::CANCEL_SUBSCRIPTION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $subscription = $subscriptionManager->cancelPlan($customer);

        $response = $event->getResponse();
        if ($response === null) {
            $response = $this->redirectToRoute('warble_media_phoenix_settings_subscription');
        }

        $event = new SubscriptionResponseEvent($subscription, $request, $response);
        $dispatcher->dispatch(PhoenixEvents::CANCEL_SUBSCRIPTION_COMPLETED, $event);

        return $response;
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

        $activePlan = null;
        if ($subscription) {
            $activePlan = $planManager->getPlan($subscription->getStripePlan());
        }

        $form = $formFactory->createForm([
            'is_new_subscription' => true,
        ]);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paymentMethodAction(Request $request)
    {
        $dispatcher = $this->get('event_dispatcher');
        $formFactory = $this->get('warble_media_phoenix.form.payment_method_factory');
        $customerManager = $this->get('warble_media_phoenix.model.customer_manager');

        $user = $this->getUserOrError();
        $customer = $user->getCustomer();

        $event = new CustomerRequestEvent($customer, $request);
        $dispatcher->dispatch(PhoenixEvents::PAYMENT_METHOD_INITIALIZE, $event);

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();

        if ($form->handleRequest($request)->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::PAYMENT_METHOD_SUCCESS, $event);

            $data = $form->getData();
            $customerManager->changePaymentMethod($customer, $data['stripeToken']);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_settings_payment_method');
            }

            $event = new CustomerResponseEvent($customer, $request, $response);
            $dispatcher->dispatch(PhoenixEvents::PAYMENT_METHOD_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Settings:payment_method.html.twig', [
            'customer' => $customer,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invoicesAction(Request $request)
    {
        $user = $this->getUserOrError();
        $customer = $user->getCustomer();

        return $this->render('WarbleMediaPhoenixBundle:Settings:invoices.html.twig', [
            'invoices' => $customer->getInvoices(),
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
        $dispatcher = $this->get('event_dispatcher');
        $formFactory = $this->get('warble_media_phoenix.form.subscription_factory');
        $planManager = $this->get('warble_media_phoenix.billing.plan_manager');
        $subscriptionManager = $this->get('warble_media_phoenix.model.subscription_manager');

        $event = new SubscriptionRequestEvent($subscription, $request);
        $dispatcher->dispatch(PhoenixEvents::UPDATE_SUBSCRIPTION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $activePlan = null;
        if ($subscription) {
            $activePlan = $planManager->getPlan($subscription->getStripePlan());
        }

        $form = $formFactory->createForm();
        $form->setData(['plan' => $activePlan]);

        if ($form->handleRequest($request)->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::UPDATE_SUBSCRIPTION_SUCCESS, $event);

            $data = $form->getData();
            $subscription = $subscriptionManager->switchCustomerToPlan($customer, $data['plan']);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_settings_subscription');
            }

            $event = new SubscriptionResponseEvent($subscription, $request, $response);
            $dispatcher->dispatch(PhoenixEvents::UPDATE_SUBSCRIPTION_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Settings:subscription.html.twig', [
            'user'         => $customer,
            'activePlan'   => $activePlan,
            'subscription' => $subscription,
            'form'         => $form->createView(),
        ]);
    }
}
