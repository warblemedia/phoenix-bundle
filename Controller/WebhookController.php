<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarbleMedia\PhoenixBundle\Event\StripeEvents;
use WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent;

class WebhookController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stripeAction(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        try {
            $stripeKey = $this->getParameter('warble_media_phoenix.stripe.secret_key');
            $stripeEvent = StripeEvent::retrieve($payload['id'], ['api_key' => $stripeKey]);
        } catch (\Exception $e) {
            return new Response('ok', Response::HTTP_OK);
        }

        $event = new StripeWebhookEvent($stripeEvent);

        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(StripeEvents::ALL, $event);
        $dispatcher->dispatch('warble_media_phoenix.webhook.stripe.' . $stripeEvent->type, $event);

        $response = $event->getResponse();
        if ($response === null) {
            $response = new Response('ok', Response::HTTP_OK);
        }

        return $response;
    }
}
