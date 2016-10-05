<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface;

class CustomerManager extends UserManager implements CustomerManagerInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface */
    private $paymentProcessor;

    /** @var int */
    private $trialDays;

    /** @var string */
    private $customerClass;

    /**
     * CustomerManager constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $passwordEncoder
     * @param \Doctrine\ORM\EntityManager                                      $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PaymentProcessorInterface     $paymentProcessor
     * @param int                                                              $trialDays
     * @param string                                                           $customerClass
     */
    public function __construct(EncoderFactoryInterface $passwordEncoder, EntityManager $manager, PaymentProcessorInterface $paymentProcessor, int $trialDays, string $customerClass)
    {
        parent::__construct($passwordEncoder, $manager, $customerClass);
        $this->paymentProcessor = $paymentProcessor;
        $this->trialDays = $trialDays;
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
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    public function createUser(): UserInterface
    {
        $trialEndsAt = new \DateTime();
        $trialEndsAt->modify($this->trialDays . ' day');

        /** @var \WarbleMedia\PhoenixBundle\Model\CustomerInterface $user */
        $user = parent::createUser();
        $user->setTrialEndsAt($trialEndsAt);

        return $user;
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
}
