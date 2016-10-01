<?php

namespace WarbleMedia\PhoenixBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WarbleMediaPhoenixExtension extends Extension
{
    /**
     * @param array                                                   $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('forms.yml');
        $loader->load('listeners.yml');
        $loader->load('security.yml');

        $container->setParameter('warble_media_phoenix.firewall_name', $config['firewall_name']);
        $container->setParameter('warble_media_phoenix.developer_emails', $config['developer_emails']);
        $container->setParameter('warble_media_phoenix.support_email_address', $config['support_email_address']);

        $container->setParameter('warble_media_phoenix.models.user_class', $config['models']['user_class']);

        $container->setParameter('warble_media_phoenix.forms.registration.name', $config['forms']['registration']['name']);
        $container->setParameter('warble_media_phoenix.forms.registration.type', $config['forms']['registration']['type']);
        $container->setParameter('warble_media_phoenix.forms.registration.validation_groups', $config['forms']['registration']['validation_groups']);
    }
}
