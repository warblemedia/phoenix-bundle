<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use WarbleMedia\PhoenixBundle\Model\InvoiceInterface;

class InvoiceEvent extends Event
{
    /** @var \WarbleMedia\PhoenixBundle\Model\InvoiceInterface */
    protected $invoice;

    /** @var \WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent */
    protected $webhookEvent;

    /**
     * InvoiceEvent constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface   $invoice
     * @param \WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent $webhookEvent
     */
    public function __construct(InvoiceInterface $invoice, StripeWebhookEvent $webhookEvent)
    {
        $this->invoice = $invoice;
        $this->webhookEvent = $webhookEvent;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface
     */
    public function getInvoice(): InvoiceInterface
    {
        return $this->invoice;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent
     */
    public function getWebhookEvent(): StripeWebhookEvent
    {
        return $this->webhookEvent;
    }
}
