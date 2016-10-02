<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use WarbleMedia\PhoenixBundle\Model\UserInterface;

abstract class Controller extends BaseController
{
    /**
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    protected function getUserOrError()
    {
        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            return $user;
        }

        throw $this->createAccessDeniedException('This user does not have access to this section.');
    }
}
