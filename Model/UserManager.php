<?php

namespace WarbleMedia\PhoenixBundle\Model;

class UserManager implements UserManagerInterface
{
    /** @var string */
    private $userClass;

    /**
     * UserManager constructor.
     *
     * @param string $userClass
     */
    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    public function createUser(): UserInterface
    {
        return new $this->userClass;
    }
}
