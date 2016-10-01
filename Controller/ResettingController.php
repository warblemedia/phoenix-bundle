<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResettingController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestAction()
    {
        return $this->render('WarbleMediaPhoenixBundle:Resetting:request.html.twig');
    }
}
