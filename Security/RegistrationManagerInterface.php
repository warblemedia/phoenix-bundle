<?php

namespace WarbleMedia\PhoenixBundle\Security;

use WarbleMedia\PhoenixBundle\Model\UserInterface;

interface RegistrationManagerInterface
{
    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    public function registerUser(UserInterface $user);
}
