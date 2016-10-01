<?php

namespace WarbleMedia\PhoenixBundle\Security;

use WarbleMedia\PhoenixBundle\Model\UserInterface;
use WarbleMedia\PhoenixBundle\Model\UserManagerInterface;

class RegistrationManager
{
    /** @var \WarbleMedia\PhoenixBundle\Model\UserManagerInterface */
    private $userManager;

    /**
     * RegistrationManager constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    public function registerUser(UserInterface $user)
    {
        $this->userManager->updateUser($user);
    }
}
