<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WarbleMediaPhoenixBundle:Default:index.html.twig');
    }
}
