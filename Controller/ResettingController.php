<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ResettingController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestAction()
    {
        return $this->render('WarbleMediaPhoenixBundle:Resetting:request.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendEmailAction(Request $request)
    {
    }
}
