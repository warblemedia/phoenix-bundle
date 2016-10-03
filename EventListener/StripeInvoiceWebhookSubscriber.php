<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Stripe\Invoice as StripeInvoice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WarbleMedia\PhoenixBundle\Event\StripeEvents;
use WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent;
use WarbleMedia\PhoenixBundle\Model\CustomerManagerInterface;
use WarbleMedia\PhoenixBundle\Model\InvoiceManagerInterface;

class StripeInvoiceWebhookSubscriber implements EventSubscriberInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Model\CustomerManagerInterface */
    private $customerManager;

    /** @var \WarbleMedia\PhoenixBundle\Model\InvoiceManagerInterface */
    private $invoiceManager;

    /** @var string */
    private $stripeKey;

    /**
     * StripeInvoiceWebhookSubscriber constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerManagerInterface $customerManager
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceManagerInterface  $invoiceManager
     * @param string                                                    $stripeKey
     */
    public function __construct(CustomerManagerInterface $customerManager, InvoiceManagerInterface $invoiceManager, string $stripeKey)
    {
        $this->customerManager = $customerManager;
        $this->invoiceManager = $invoiceManager;
        $this->stripeKey = $stripeKey;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            StripeEvents::INVOICE_PAYMENT_SUCCEEDED => 'onInvoicePaymentSucceeded',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent $event
     */
    public function onInvoicePaymentSucceeded(StripeWebhookEvent $event)
    {
        $stripeEvent = $event->getStripeEvent();
        $stripeCustomerId = $stripeEvent->data->object->customer;
        $stripeInvoiceId = $stripeEvent->data->object->id;

        $customer = $this->customerManager->findCustomerByStripeId($stripeCustomerId);

        if ($customer) {
            $stripeInvoice = StripeInvoice::retrieve($stripeInvoiceId, $this->stripeKey);
            $invoice = $customer->getInvoiceByStripeId($stripeInvoice->id);

            if ($invoice === null) {
                $invoice = $this->invoiceManager->createInvoice($customer);
            }

            $invoice->setStripeId($stripeInvoice->id);
            $invoice->setTaxAmount(bcdiv($stripeInvoice->tax, 100, 2));
            $invoice->setTotalAmount(bcdiv($this->calculateTotalAmount($stripeInvoice), 100, 2));

            $this->invoiceManager->updateInvoice($invoice);

            // TODO: Dispatch phoenix events to notify user of invoice
        }
    }

    /**
     * @param \Stripe\Invoice $stripeInvoice
     * @return int
     */
    protected function calculateTotalAmount(StripeInvoice $stripeInvoice): int
    {
        $startingBalance = $stripeInvoice->starting_balance ?? 0;

        return max(0, $stripeInvoice->total + $startingBalance);
    }
}
