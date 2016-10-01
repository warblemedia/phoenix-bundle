<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\UserEvents;
use WarbleMedia\PhoenixBundle\Event\UserResponseEvent;
use WarbleMedia\PhoenixBundle\Mailer\Mail\ResettingMail;

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
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $tokenGenerator = $this->get('warble_media_phoenix.util.token_generator');
        $tokenTimeout = $this->getParameter('warble_media_phoenix.resetting.token_ttl');

        $user = $userManager->findUserByEmail($request->request->get('email'));

        if ($user !== null && !$user->isPasswordRequestNonExpired($tokenTimeout)) {
            if ($user->getConfirmationToken() === null) {
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            $user->setPasswordRequestedAt(new \DateTime());
            $this->get('warble_media_phoenix.mailer')->send(new ResettingMail($user));

            $userManager->updateUser($user);
        }

        return $this->redirectToRoute('warble_media_phoenix_resetting_check_email', [
            'email' => $user->getEmail(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->query->get('email');

        if (empty($email)) {
            return $this->redirectToRoute('warble_media_phoenix_resetting_request');
        }

        return $this->render('WarbleMediaPhoenixBundle:Resetting:check_email.html.twig', [
            'email' => $email,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string                                    $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resetAction(Request $request, $token)
    {
        $dispatcher = $this->get('event_dispatcher');
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $formFactory = $this->get('warble_media_phoenix.form.resetting_factory');

        $user = $userManager->findUserByConfirmationToken($token);

        if ($user === null) {
            throw $this->createNotFoundException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        if ($form->handleRequest($request)->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(UserEvents::RESETTING_RESET_SUCCESS, $event);

            $userManager->updateUser($user);

            $response = $event->getResponse();
            if ($response === null) {
                // TODO: Find more appropriate redirect
                $response = $this->redirect('/');
            }

            $event = new UserResponseEvent($user, $request, $response);
            $dispatcher->dispatch(UserEvents::RESETTING_RESET_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Resetting:reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
