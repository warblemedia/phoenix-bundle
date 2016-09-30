<?php

namespace WarbleMedia\PhoenixBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarbleMedia\PhoenixBundle\Model\UserInterface;

class UserResponseEvent extends UserEvent
{
    /** @var \Symfony\Component\HttpFoundation\Response */
    private $response;

    public function __construct(UserInterface $user, Request $request, Response $response)
    {
        parent::__construct($user, $request);
        $this->response = $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
