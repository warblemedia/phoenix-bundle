<?php

namespace WarbleMedia\PhoenixBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PhoenixVariable
{
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    /**
     * PhoenixVariable constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getSupportEmail()
    {
        return $this->container->getParameter('warble_media_phoenix.support_email_address');
    }

    /**
     * @return string
     */
    public function getStripeKey()
    {
        return $this->container->getParameter('warble_media_phoenix.stripe.publishable_key');
    }
}
