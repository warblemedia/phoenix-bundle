<?php

namespace WarbleMedia\PhoenixBundle\Controller;

class DeveloperController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metricsAction()
    {
        return $this->render('WarbleMediaPhoenixBundle:Developer:metrics.html.twig', [
            // ...
        ]);
    }
}
