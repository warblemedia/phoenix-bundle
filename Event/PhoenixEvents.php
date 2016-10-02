<?php

namespace WarbleMedia\PhoenixBundle\Event;

final class PhoenixEvents
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
    const PROFILE_EDIT_INITIALIZE = 'warble_media_phoenix.profile.edit.initialize';

    /**
     * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const PROFILE_EDIT_SUCCESS = 'warble_media_phoenix.profile.edit.success';

    /**
     * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserResponseEvent")
     */
    const PROFILE_EDIT_COMPLETED = 'warble_media_phoenix.profile.edit.completed';

    /**
     * The CHANGE_PASSWORD_INITIALIZE event occurs when the change password process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserRequestEvent")
     */
    const CHANGE_PASSWORD_INITIALIZE = 'warble_media_phoenix.change_password.initialize';
    /**
     * The CHANGE_PASSWORD_SUCCESS event occurs when the change password form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const CHANGE_PASSWORD_SUCCESS = 'warble_media_phoenix.change_password.success';
    /**
     * The CHANGE_PASSWORD_COMPLETED event occurs after saving the user in the change password process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserResponseEvent")
     */
    const CHANGE_PASSWORD_COMPLETED = 'warble_media_phoenix.change_password.completed';

    /**
     * The NEW_SUBSCRIPTION_INITIALIZE event occurs when the new subscription process is initialized with a cancelled subscription.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\SubscriptionRequestEvent")
     */
    const NEW_SUBSCRIPTION_INITIALIZE = 'warble_media_phoenix.subscription.create.initialize';

    /**
     * The NEW_SUBSCRIPTION_SUCCESS event occurs when the new subscription form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const NEW_SUBSCRIPTION_SUCCESS = 'warble_media_phoenix.subscription.create.success';

    /**
     * The NEW_SUBSCRIPTION_COMPLETED event occurs after saving the subscription in the new subscription process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\SubscriptionResponseEvent")
     */
    const NEW_SUBSCRIPTION_COMPLETED = 'warble_media_phoenix.subscription.create.completed';

    /**
     * The UPDATE_SUBSCRIPTION_INITIALIZE event occurs when the update subscription process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\SubscriptionRequestEvent")
     */
    const UPDATE_SUBSCRIPTION_INITIALIZE = 'warble_media_phoenix.subscription.update.initialize';

    /**
     * The UPDATE_SUBSCRIPTION_SUCCESS event occurs when the update subscription form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\FormEvent")
     */
    const UPDATE_SUBSCRIPTION_SUCCESS = 'warble_media_phoenix.subscription.update.success';

    /**
     * The UPDATE_SUBSCRIPTION_COMPLETED event occurs after saving the subscription in the update subscription process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\SubscriptionResponseEvent")
     */
    const UPDATE_SUBSCRIPTION_COMPLETED = 'warble_media_phoenix.subscription.update.completed';

    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the user that was authenticated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\UserEvent")
     */
    const SECURITY_IMPLICIT_LOGIN = 'warble_media_phoenix.security.implicit_login';
}
