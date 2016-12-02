<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
use WarbleMedia\PhoenixBundle\Event\UserResponseEvent;
use WarbleMedia\PhoenixBundle\Model\UserInterface;

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
        $registrationManager = $this->get('warble_media_phoenix.security.registration_manager');

        $user = $userManager->createUser();
        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::REGISTRATION_SUCCESS, $event);

            $registrationManager->registerUser($user);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_register_confirmed');
            }

            $event = new UserResponseEvent($user, $request, $response);
            $dispatcher->dispatch(PhoenixEvents::REGISTRATION_COMPLETED, $event);

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
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('WarbleMediaPhoenixBundle:Registration:confirmed.html.twig', [
            'user'      => $user,
            'targetUrl' => $this->getTargetUrlFromSession(),
        ]);
    }

    /**
     * @return string|null
     */
    private function getTargetUrlFromSession()
    {
        $token = $this->get('security.token_storage')->getToken();

        if ($token instanceof UsernamePasswordToken) {
            $key = sprintf('_security.%s.target_path', $token->getProviderKey());

            return $this->get('session')->get($key);
        }

        return null;
    }
}
