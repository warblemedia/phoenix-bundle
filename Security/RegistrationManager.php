<?php

namespace WarbleMedia\PhoenixBundle\Security;

use WarbleMedia\PhoenixBundle\Model\UserInterface;
use WarbleMedia\PhoenixBundle\Model\UserManagerInterface;

class RegistrationManager implements RegistrationManagerInterface
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
        $user->setEnabled(true);

        $this->userManager->updateUser($user);
    }
}
