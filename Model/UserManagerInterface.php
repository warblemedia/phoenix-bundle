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
     * @param string $email
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface|null
     */
    public function findUserByEmail(string $email);

    /**
     * @param string $token
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface|null
     */
    public function findUserByConfirmationToken(string $token);
}
