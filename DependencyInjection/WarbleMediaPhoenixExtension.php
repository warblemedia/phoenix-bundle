<?php

namespace WarbleMedia\PhoenixBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WarbleMediaPhoenixExtension extends Extension implements CompilerPassInterface
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

        $container->setParameter('warble_media_phoenix.profile_photos.base_url', $config['profile_photos']['base_url']);
        $container->setParameter('warble_media_phoenix.profile_photos.base_path', $config['profile_photos']['base_path']);

        $container->setParameter('warble_media_phoenix.models.user_class', $config['models']['user_class']);

        foreach ($config['forms'] as $key => $form) {
            $container->setParameter("warble_media_phoenix.forms.{$key}.name", $form['name']);
            $container->setParameter("warble_media_phoenix.forms.{$key}.type", $form['type']);
            if (array_key_exists('validation_groups', $form)) {
                $container->setParameter("warble_media_phoenix.forms.{$key}.validation_groups", $form['validation_groups']);
            }
        }

        $container->setParameter('warble_media_phoenix.resetting.token_ttl', $config['resetting']['token_ttl']);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $twig = $container->findDefinition('twig');
        $twig->addMethodCall('addGlobal', ['phoenix', new Reference('warble_media_phoenix.twig.phoenix_variable')]);
    }
}
