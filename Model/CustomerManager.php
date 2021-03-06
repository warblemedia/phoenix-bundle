<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\ORM\EntityManager;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;

class CustomerManager implements CustomerManagerInterface
{
    /** @var \Doctrine\ORM\EntityManager */
    private $manager;

    /** @var \Doctrine\ORM\EntityRepository */
    private $repository;

    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /** @var string */
    private $customerClass;

    /**
     * CustomerManager constructor.
     *
     * @param \Doctrine\ORM\EntityManager                                  $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface $paymentProcessor
     * @param string                                                       $customerClass
     */
    public function __construct(EntityManager $manager, PaymentProcessorInterface $paymentProcessor, string $customerClass)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository($customerClass);
        $this->paymentProcessor = $paymentProcessor;
        $this->customerClass = $customerClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @param string                                             $stripeToken
     */
    public function changePaymentMethod(CustomerInterface $customer, string $stripeToken)
    {
        $this->manager->transactional(function () use ($customer, $stripeToken) {
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
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null
     */
    public function findCustomerByStripeId(string $stripeId)
    {
        return $this->repository->findOneBy(['stripeId' => $stripeId]);
    }
}
