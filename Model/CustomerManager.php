<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;

class CustomerManager implements CustomerManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /** @var string */
    private $customerClass;

    /**
     * CustomerManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                   $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface $paymentProcessor
     * @param string                                                       $customerClass
     */
    public function __construct(ObjectManager $manager, PaymentProcessorInterface $paymentProcessor, string $customerClass)
    {
        $this->manager = $manager;
        $this->paymentProcessor = $paymentProcessor;
        $this->customerClass = $customerClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $stripeToken
     */
    public function changePaymentMethod(CustomerInterface $customer, string $stripeToken)
    {
        $this->transactional(function () use ($customer, $stripeToken) {
            $this->paymentProcessor->updatePaymentMethod($customer, $stripeToken);

            $this->updateCustomer($customer);
        });
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param bool                                               $flush
     */
    public function updateCustomer(CustomerInterface $customer, bool $flush = true)
    {
        $this->manager->persist($customer);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function transactional(callable $callback)
    {
        if ($this->manager instanceof EntityManager) {
            return $this->manager->transactional($callback);
        } else {
            return $callback();
        }
    }
}
