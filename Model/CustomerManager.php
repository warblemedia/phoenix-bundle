<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;

class CustomerManager extends UserManager implements CustomerManagerInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /** @var string */
    private $customerClass;

    /**
     * CustomerManager constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $passwordEncoder
     * @param \Doctrine\Common\Persistence\ObjectManager                       $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface     $paymentProcessor
     * @param string                                                           $customerClass
     */
    public function __construct(EncoderFactoryInterface $passwordEncoder, ObjectManager $manager, PaymentProcessorInterface $paymentProcessor, string $customerClass)
    {
        parent::__construct($passwordEncoder, $manager, $customerClass);
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
        $this->updateUser($customer, $flush);
    }

    /**
     * @param string $stripeId
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null
     */
    public function findCustomerByStripeId(string $stripeId)
    {
        return $this->repository->findOneBy(['stripeId' => $stripeId]);
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
