<?php

namespace WarbleMedia\PhoenixBundle\Model;

abstract class User implements UserInterface
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $email;

    /** @var string */
    protected $salt;

    /** @var string */
    protected $password;

    /** @var string */
    protected $plainPassword;

    /** @var array */
    protected $roles;

    /** @var bool */
    protected $locked;

    /** @var bool */
    protected $enabled;

    /** @var bool */
    protected $expired;

    /** @var \DateTime|null */
    protected $expiresAt;

    /** @var bool */
    protected $credentialsExpired;

    /** @var \DateTime|null */
    protected $credentialsExpireAt;

    /** @var string */
    protected $confirmationToken;

    /** @var \DateTime|null */
    protected $passwordRequestedAt;

    /** @var \DateTime|null */
    protected $lastLogin;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->roles = [];
        $this->locked = false;
        $this->enabled = false;
        $this->expired = false;
        $this->credentialsExpired = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $role = strtoupper($role);

        return in_array($role, $this->getRoles(), true);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * @param string $role
     */
    public function addRole(string $role)
    {
        $role = strtoupper($role);

        if (!$this->hasRole($role)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param string $role
     */
    public function removeRole(string $role)
    {
        $role = strtoupper($role);
        $key = array_search($role, $this->roles, true);

        if ($key !== false) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return !$this->isLocked();
    }

    /**
     * @param bool $locked
     */
    public function setLocked(bool $locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        if ($this->expired) {
            return true;
        }

        if ($this->expiresAt !== null && $this->expiresAt->getTimestamp() >= time()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired(): bool
    {
        return !$this->isExpired();
    }

    /**
     * @param bool $expired
     */
    public function setExpired(bool $expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime|null $expiresAt
     */
    public function setExpiresAt(\DateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return bool
     */
    public function isCredentialsExpired(): bool
    {
        if ($this->credentialsExpired) {
            return true;
        }

        if ($this->credentialsExpireAt !== null && $this->credentialsExpireAt->getTimestamp() >= time()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired(): bool
    {
        return !$this->isCredentialsExpired();
    }

    /**
     * @param bool $credentialsExpired
     */
    public function setCredentialsExpired(bool $credentialsExpired)
    {
        $this->credentialsExpired = $credentialsExpired;
    }

    /**
     * @return \DateTime|null
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * @param \DateTime|null $credentialsExpireAt
     */
    public function setCredentialsExpireAt(\DateTime $credentialsExpireAt = null)
    {
        $this->credentialsExpireAt = $credentialsExpireAt;
    }

    /**
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param string $confirmationToken
     */
    public function setConfirmationToken(string $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime|null $date
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;
    }

    /**
     * @param int $ttl
     * @return bool
     */
    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $time
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;
    }
}
