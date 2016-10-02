<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
use WarbleMedia\PhoenixBundle\Event\SubscriptionResponseEvent;

class BillingController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscriptionAction(Request $request)
    {
        $user = $this->getUserOrError();
        $dispatcher = $this->get('event_dispatcher');
        $formFactory = $this->get('warble_media_phoenix.form.subscription_factory');
        $subscriptionManager = $this->get('warble_media_phoenix.model.subscription_manager');

        $form = $formFactory->createForm();

        if ($form->handleRequest($request)->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::NEW_SUBSCRIPTION_SUCCESS, $event);

            $data = $form->getData();
            $customer = $user->getCustomer();
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
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
