<?php

namespace WarbleMedia\PhoenixBundle\Event;

final class UserEvents
{
    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const REGISTRATION_SUCCESS = 'warble_media_phoenix.registration.success';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("WarbleMedia\PhoenixBundle\Event\UserResponseEvent")
     */
    const REGISTRATION_COMPLETED = 'warble_media_phoenix.registration.completed';
}
