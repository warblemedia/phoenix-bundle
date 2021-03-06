<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WarbleMedia\PhoenixBundle\Event\FormEvent;
use WarbleMedia\PhoenixBundle\Event\PhoenixEvents;
use WarbleMedia\PhoenixBundle\Event\UserRequestEvent;
use WarbleMedia\PhoenixBundle\Event\UserResponseEvent;

class SettingsController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUserOrError();
        $dispatcher = $this->get('event_dispatcher');
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $formFactory = $this->get('warble_media_phoenix.form.profile_factory');

        $event = new UserRequestEvent($user, $request);
        $dispatcher->dispatch(PhoenixEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_settings_profile');
            }

            $event = new UserResponseEvent($user, $request, $response);
            $dispatcher->dispatch(PhoenixEvents::PROFILE_EDIT_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Settings:profile.html.twig', [
            'user'           => $user,
            'form'           => $form->createView(),
            'photoUploadKey' => $this->getPhotoUploadKey(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profilePhotoAction(Request $request)
    {
        $user = $this->getUserOrError();
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $formFactory = $this->get('warble_media_phoenix.form.profile_photo_factory');
        $photoManager = $this->get('warble_media_phoenix.model.user_photo_manager');

        $form = $formFactory->createForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('profile_photo')->getData();
            $photoUrl = $photoManager->uploadPhoto($file);

            $user->setPhotoUrl($photoUrl);
            $userManager->updateUser($user);

            return $this->json([
                'success'   => true,
                'photo_url' => $photoUrl,
            ]);
        }

        // TODO: Respond with error message
        return $this->json(['success' => false], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function securityAction(Request $request)
    {
        $user = $this->getUserOrError();
        $dispatcher = $this->get('event_dispatcher');
        $userManager = $this->get('warble_media_phoenix.model.user_manager');
        $formFactory = $this->get('warble_media_phoenix.form.change_password_factory');

        $event = new UserRequestEvent($user, $request);
        $dispatcher->dispatch(PhoenixEvents::CHANGE_PASSWORD_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(PhoenixEvents::CHANGE_PASSWORD_SUCCESS, $event);

            $userManager->updateUser($user);

            $response = $event->getResponse();
            if ($response === null) {
                $response = $this->redirectToRoute('warble_media_phoenix_settings_security');
            }

            $event = new UserResponseEvent($user, $request, $response);
            $dispatcher->dispatch(PhoenixEvents::CHANGE_PASSWORD_COMPLETED, $event);

            return $response;
        }

        return $this->render('WarbleMediaPhoenixBundle:Settings:security.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function getPhotoUploadKey()
    {
        $formFactory = $this->get('warble_media_phoenix.form.profile_photo_factory');
        $form = $formFactory->createForm();
        $view = $form->createView();

        return $view->children['profile_photo']->vars['full_name'];
    }
}
