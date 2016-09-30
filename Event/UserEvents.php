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
}
