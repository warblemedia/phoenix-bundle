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
        $formFactory = $this->get('warble_media_phoenix.form.subscription_factory');

        $form = $formFactory->createForm();

        if ($form->handleRequest($request)->isValid()) {
            // TODO: Handle form submission...
        }

        return $this->render('WarbleMediaPhoenixBundle:Settings:subscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
