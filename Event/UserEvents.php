<?php

namespace WarbleMedia\PhoenixBundle\Event;

final class UserEvents
{
    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const REGISTRATION_SUCCESS = 'warble_media_phoenix.registration.success';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserResponseEvent")
     */
    const REGISTRATION_COMPLETED = 'warble_media_phoenix.registration.completed';

    /**
     * The RESETTING_RESET_INITIALIZE event occurs when the resetting process is initialized.
     *
     * This event allows you to set the response to bypass the processing.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserRequestEvent")
     */
    const RESETTING_RESET_INITIALIZE = 'warble_media_phoenix.resetting.reset.initialize';

    /**
     * The RESETTING_RESET_SUCCESS event occurs when the resetting form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const RESETTING_RESET_SUCCESS = 'warble_media_phoenix.resetting.reset.success';

    /**
     * The RESETTING_RESET_COMPLETED event occurs after saving the user in the resetting process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserResponseEvent")
     */
    const RESETTING_RESET_COMPLETED = 'warble_media_phoenix.resetting.reset.completed';

    /**
     * The PROFILE_EDIT_INITIALIZE event occurs when the profile editing process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserRequestEvent")
     */
    const PROFILE_EDIT_INITIALIZE = 'fos_user.profile.edit.initialize';

    /**
     * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const PROFILE_EDIT_SUCCESS = 'fos_user.profile.edit.success';

    /**
     * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserResponseEvent")
     */
    const PROFILE_EDIT_COMPLETED = 'fos_user.profile.edit.completed';

    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the user that was authenticated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserEvent")
     */
    const SECURITY_IMPLICIT_LOGIN = 'warble_media_phoenix.security.implicit_login';
}
