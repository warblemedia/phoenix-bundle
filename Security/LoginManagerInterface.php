<?php

namespace WarbleMedia\PhoenixBundle\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

interface LoginManagerInterface
{
    /**
     * @param string                                              $firewall
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param \Symfony\Component\HttpFoundation\Response|null     $response
     */
    public function loginUser(string $firewall, UserInterface $user, Response $response = null);
}
