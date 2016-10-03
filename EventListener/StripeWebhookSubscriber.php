<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WarbleMedia\PhoenixBundle\Event\StripeEvents;
use WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent;

class StripeWebhookSubscriber implements EventSubscriberInterface
{
    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * StripeWebhookSubscriber constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            StripeEvents::ALL => 'logEvent',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent $event
     */
    public function logEvent(StripeWebhookEvent $event)
    {
        $stripeEvent = $event->getStripeEvent();

        $this->logger->info('Received stripe event "{type}"', [
            'type'    => $stripeEvent->type,
            'payload' => $stripeEvent->jsonSerialize(),
        ]);
    }
}
