<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use WarbleMedia\PhoenixBundle\Event\UserEvents;
use WarbleMedia\PhoenixBundle\Event\UserResponseEvent;
use WarbleMedia\PhoenixBundle\Security\LoginManager;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Security\LoginManager */
    private $loginManager;

    /** @var string */
    private $firewallName;

    /**
     * AuthenticationSubscriber constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Security\LoginManager $loginManager
     * @param string                                           $firewallName
     */
    public function __construct(LoginManager $loginManager, string $firewallName)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = $firewallName;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::REGISTRATION_COMPLETED => 'authenticate',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\UserResponseEvent $event
     */
    public function authenticate(UserResponseEvent $event)
    {
        try {
            $user = $event->getUser();
            $this->loginManager->loginUser($this->firewallName, $user, $event->getResponse());
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
