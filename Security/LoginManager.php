<?php

namespace WarbleMedia\PhoenixBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

class LoginManager implements LoginManagerInterface
{
    /** @var \Symfony\Component\Security\Core\User\UserChecker */
    private $userChecker;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
    private $tokenStorage;

    /** @var \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface */
    private $sessionStrategy;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    /**
     * AuthenticationSubscriber constructor.
     *
     * @param \Symfony\Component\Security\Core\User\UserChecker                                   $userChecker
     * @param \Symfony\Component\HttpFoundation\RequestStack                                      $requestStack
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface     $sessionStrategy
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                           $container
     */
    public function __construct(UserChecker $userChecker, RequestStack $requestStack, TokenStorageInterface $tokenStorage, SessionAuthenticationStrategyInterface $sessionStrategy, ContainerInterface $container)
    {
        $this->userChecker = $userChecker;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->sessionStrategy = $sessionStrategy;
        $this->container = $container;
    }

    /**
     * @param string                                              $firewall
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param \Symfony\Component\HttpFoundation\Response|null     $response
     */
    public function loginUser(string $firewall, UserInterface $user, Response $response = null)
    {
        $this->userChecker->checkPreAuth($user);

        $token = $this->createToken($firewall, $user);
        $request = $this->requestStack->getCurrentRequest();

        if ($request !== null) {
            $this->sessionStrategy->onAuthentication($request, $token);

            if ($response !== null) {
                $this->rememberMe($firewall, $request, $response, $token);
            }
        }

        $this->tokenStorage->setToken($token);
    }

    /**
     * @param string                                              $firewall
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     */
    protected function createToken(string $firewall, UserInterface $user): UsernamePasswordToken
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request                            $request
     * @param \Symfony\Component\HttpFoundation\Response                           $response
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     */
    protected function rememberMe(string $firewall, Request $request, Response $response, TokenInterface $token)
    {
        $persistentService = 'security.authentication.rememberme.services.persistent.' . $firewall;
        $simpleHashService = 'security.authentication.rememberme.services.simplehash.' . $firewall;
        $rememberMeServices = null;

        if ($this->container->has($persistentService)) {
            $rememberMeServices = $this->container->get($persistentService);
        } elseif ($this->container->has($simpleHashService)) {
            $rememberMeServices = $this->container->get($simpleHashService);
        }

        if ($rememberMeServices instanceof RememberMeServicesInterface) {
            $rememberMeServices->loginSuccess($request, $response, $token);
        }
    }
}
