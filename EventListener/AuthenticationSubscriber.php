<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
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
            PhoenixEvents::REGISTRATION_COMPLETED    => 'authenticate',
            PhoenixEvents::RESETTING_RESET_COMPLETED => 'authenticate',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\UserResponseEvent          $event
     * @param string                                                      $eventName
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function authenticate(UserResponseEvent $event, string $eventName, EventDispatcherInterface $dispatcher)
    {
        try {
            $this->loginManager->loginUser($this->firewallName, $event->getUser(), $event->getResponse());
            $dispatcher->dispatch(PhoenixEvents::SECURITY_IMPLICIT_LOGIN, $event);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
