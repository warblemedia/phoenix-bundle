<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use WarbleMedia\PhoenixBundle\Event\UserEvent;
use WarbleMedia\PhoenixBundle\Event\UserEvents;
use WarbleMedia\PhoenixBundle\Model\UserInterface;
use WarbleMedia\PhoenixBundle\Model\UserManagerInterface;

class LastLoginSubscriber implements EventSubscriberInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Model\UserManagerInterface */
    private $userManager;

    /**
     * LastLoginSubscriber constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Model\UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
            SecurityEvents::INTERACTIVE_LOGIN   => 'onInteractiveLogin',
        ];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Event\UserEvent $event
     */
    public function onImplicitLogin(UserEvent $event)
    {
        $user = $event->getUser();

        $this->setLastLogin($user);
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->setLastLogin($user);
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    private function setLastLogin(UserInterface $user)
    {
        $user->setLastLogin(new \DateTime());
        $this->userManager->updateUser($user);
    }
}
