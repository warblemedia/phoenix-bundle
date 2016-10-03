<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;

class InvoiceManager implements InvoiceManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /** @var string */
    private $invoiceClass;

    /**
     * InvoiceManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param string                                     $invoiceClass
     */
    public function __construct(ObjectManager $manager, string $invoiceClass)
    {
        $this->manager = $manager;
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
     * @return \WarbleMedia\PhoenixBundle\Model\InvoiceInterface
     */
    protected function createInvoiceInstance(): InvoiceInterface
    {
        return new $this->invoiceClass;
    }
}
