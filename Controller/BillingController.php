<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class BillingController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscriptionAction(Request $request)
    {
        return $this->render('WarbleMediaPhoenixBundle:Settings:subscription.html.twig', [
        ]);
    }
}
