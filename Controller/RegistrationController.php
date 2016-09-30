<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $formFactory = $this->get('warble_media_phoenix.form.registration_factory');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $form = $formFactory->createForm();
        $form->setData($user);

        if ($form->handleRequest($request)->isValid()) {
            // TODO: Handle submitted form...
        }

        return $this->render('WarbleMediaPhoenixBundle:Registration:register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
