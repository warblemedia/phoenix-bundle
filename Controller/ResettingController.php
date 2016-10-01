<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
}
