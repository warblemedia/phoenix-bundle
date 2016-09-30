<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Model\UserInterface;

class UserEvent extends Event
{
    /** @var \WarbleMedia\PhoenixBundle\Model\UserInterface */
    protected $user;

    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;

    /**
     * UserEvent constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     * @param \Symfony\Component\HttpFoundation\Request      $request
     */
    public function __construct(UserInterface $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
