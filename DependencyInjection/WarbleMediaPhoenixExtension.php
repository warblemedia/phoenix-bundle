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

        $planManager = $container->getDefinition('warble_media_phoenix.billing.plan_manager');
        foreach ($config['billing']['plans'] as $id => $plan) {
            $planManager->addMethodCall('addPlan', [$id, $plan['name'], $plan]);
        }

        $container->setParameter('warble_media_phoenix.default_locale', $config['default_locale']);
        $container->setParameter('warble_media_phoenix.firewall_name', $config['firewall_name']);
        $container->setParameter('warble_media_phoenix.trial_days', $config['trial_days']);
        $container->setParameter('warble_media_phoenix.developer_emails', $config['developer_emails']);
        $container->setParameter('warble_media_phoenix.support_email_address', $config['support_email_address']);

        $container->setParameter('warble_media_phoenix.stripe.secret_key', $config['stripe']['secret_key']);
        $container->setParameter('warble_media_phoenix.stripe.publishable_key', $config['stripe']['publishable_key']);

        $container->setParameter('warble_media_phoenix.billing.currency', $config['billing']['currency']);

        $container->setParameter('warble_media_phoenix.profile_photos.base_url', $config['profile_photos']['base_url']);
        $container->setParameter('warble_media_phoenix.profile_photos.base_path', $config['profile_photos']['base_path']);

        foreach ($config['models'] as $key => $model) {
            $container->setParameter("warble_media_phoenix.models.{$key}", $model);
        }

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
