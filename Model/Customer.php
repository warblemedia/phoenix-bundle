<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Customer extends User
{
    /** @var \Doctrine\Common\Collections\Collection */
    protected $subscriptions;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subscriptions = new ArrayCollection();
    }
}
