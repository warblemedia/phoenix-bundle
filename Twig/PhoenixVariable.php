<?php

namespace WarbleMedia\PhoenixBundle\Twig;

class PhoenixVariable
{
    /** @var string */
    private $supportEmail;

    /**
     * PhoenixVariable constructor.
     *
     * @param string $supportEmail
     */
    public function __construct($supportEmail)
    {
        $this->supportEmail = $supportEmail;
    }

    /**
     * @return string
     */
    public function getSupportEmail()
    {
        return $this->supportEmail;
    }
}
