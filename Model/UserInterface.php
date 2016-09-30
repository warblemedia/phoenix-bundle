<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     */
    public function setEmail(string $email);

    /**
     * @param string $password
     */
    public function setPassword(string $password);

    /**
     * @return string
     */
    public function getPlainPassword();

    /**
     * @param string $password
     */
    public function setPlainPassword(string $password);

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool;

    /**
     * @param array $roles
     */
    public function setRoles(array $roles);

    /**
     * @param string $role
     */
    public function addRole(string $role);

    /**
     * @param string $role
     */
    public function removeRole(string $role);

    /**
     * @param bool $locked
     */
    public function setLocked(bool $locked);

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled);

    /**
     * @return string
     */
    public function getConfirmationToken();

    /**
     * @param string $confirmationToken
     */
    public function setConfirmationToken(string $confirmationToken);

    /**
     * @param \DateTime|null $date
     */
    public function setPasswordRequestedAt(\DateTime $date = null);

    /**
     * @param int $ttl
     * @return bool
     */
    public function isPasswordRequestNonExpired(int $ttl): bool;
}
