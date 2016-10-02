<?php

namespace WarbleMedia\PhoenixBundle\Model;

class Subscription implements SubscriptionInterface
{
    /** @var mixed */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
