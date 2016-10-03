<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;

class InvoiceManager implements InvoiceManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /** @var string */
    private $invoiceClass;

    /**
     * InvoiceManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                   $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface $paymentProcessor
     * @param string                                                       $invoiceClass
     */
    public function __construct(ObjectManager $manager, PaymentProcessorInterface $paymentProcessor, string $invoiceClass)
    {
        $this->manager = $manager;
        $this->paymentProcessor = $paymentProcessor;
        $this->invoiceClass = $invoiceClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface
     */
    public function createInvoice(CustomerInterface $customer): InvoiceInterface
    {
        $invoice = $this->createInvoiceInstance();

        $customer->addInvoice($invoice);

        return $invoice;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     * @param bool                                              $flush
     */
    public function updateInvoice(InvoiceInterface $invoice, bool $flush = true)
    {
        $this->manager->persist($invoice);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     * @return \Stripe\Invoice|null
     */
    public function getStripeInvoice(InvoiceInterface $invoice)
    {
        return $this->paymentProcessor->getCustomerInvoice($invoice->getCustomer(), $invoice->getStripeId());
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface
     */
    protected function createInvoiceInstance(): InvoiceInterface
    {
        return new $this->invoiceClass;
    }
}
