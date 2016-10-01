<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface UserManagerInterface
{
    /**
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    public function createUser(): UserInterface;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    public function updateUser(UserInterface $user);

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface|null
     */
    public function findUserByEmail(string $email);
}
