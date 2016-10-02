<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface SubscriptionInterface extends ModelInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null;
     */
    public function getCustomer();

    /**
     * @internal
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface|null $customer
     */
    public function setCustomer(CustomerInterface $customer = null);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
