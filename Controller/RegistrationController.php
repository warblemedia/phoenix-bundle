<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\UserEvents;
use WarbleMedia\PhoenixBundle\Event\UserResponseEvent;

class RegistrationController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $dispatcher = $this->get('event_dispatcher');
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $formFactory = $this->get('warble_media_phoenix.form.registration_factory');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $form = $formFactory->createForm();
        $form->setData($user);

        if ($form->handleRequest($request)->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(UserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_register_confirm');
            }

            $event = new UserResponseEvent($user, $request, $response);
            $dispatcher->dispatch(UserEvents::REGISTRATION_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Registration:register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction()
    {
        // TODO: Implement confirmAction() method.
    }
}
