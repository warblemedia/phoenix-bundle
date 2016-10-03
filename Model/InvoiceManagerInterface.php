<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface InvoiceManagerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface
     */
    public function createInvoice(CustomerInterface $customer): InvoiceInterface;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     */
    public function updateInvoice(InvoiceInterface $invoice);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\InvoiceInterface $invoice
     * @return \Stripe\Invoice|null
     */
    public function getStripeInvoice(InvoiceInterface $invoice);
}
