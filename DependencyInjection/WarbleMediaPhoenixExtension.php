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

        $container->setParameter('warble_media_phoenix.default_locale', $config['default_locale']);
        $container->setParameter('warble_media_phoenix.firewall_name', $config['firewall_name']);
        $container->setParameter('warble_media_phoenix.trial_days', $config['trial_days']);
        $container->setParameter('warble_media_phoenix.product_name', $config['product_name']);
        $container->setParameter('warble_media_phoenix.developer_emails', $config['developer_emails']);
        $container->setParameter('warble_media_phoenix.support_email_address', $config['support_email_address']);

        foreach (['name', 'street', 'location', 'phone', 'email', 'url'] as $key) {
            $container->setParameter("warble_media_phoenix.vendor_details.{$key}", $config['vendor_details'][$key]);
        }

        $container->setParameter('warble_media_phoenix.stripe.secret_key', $config['stripe']['secret_key']);
        $container->setParameter('warble_media_phoenix.stripe.publishable_key', $config['stripe']['publishable_key']);

        $container->setParameter('warble_media_phoenix.billing.currency', $config['billing']['currency']);
        $container->setParameter('warble_media_phoenix.billing.prorate_plan_upgrades', $config['billing']['prorate_plan_upgrades']);

        $container->setParameter('warble_media_phoenix.profile_photos.base_url', $config['profile_photos']['base_url']);
        $container->setParameter('warble_media_phoenix.profile_photos.base_path', $config['profile_photos']['base_path']);

        foreach ($config['models'] as $key => $model) {
            $container->setParameter("warble_media_phoenix.models.{$key}", $model);
        }

        $container->setAlias('warble_media_phoenix.model.user_manager', $config['services']['user_manager']);
        $container->setAlias('warble_media_phoenix.model.user_photo_manager', $config['services']['user_photo_manager']);
        $container->setAlias('warble_media_phoenix.model.customer_manager', $config['services']['customer_manager']);
        $container->setAlias('warble_media_phoenix.model.subscription_manager', $config['services']['subscription_manager']);
        $container->setAlias('warble_media_phoenix.model.invoice_manager', $config['services']['invoice_manager']);
        $container->setAlias('warble_media_phoenix.model.metrics_manager', $config['services']['metrics_manager']);
        $container->setAlias('warble_media_phoenix.performance.indicators', $config['services']['indicators']);
        $container->setAlias('warble_media_phoenix.security.registration_manager', $config['services']['registration_manager']);
        $container->setAlias('warble_media_phoenix.security.login_manager', $config['services']['login_manager']);
        $container->setAlias('warble_media_phoenix.mailer', $config['services']['mailer']);
        $container->setAlias('warble_media_phoenix.billing.plan_manager', $config['services']['plan_manager']);
        $container->setAlias('warble_media_phoenix.billing.payment_processor', $config['services']['payment_processor']);
        $container->setAlias('warble_media_phoenix.util.token_generator', $config['services']['token_generator']);

        foreach ($config['forms'] as $key => $form) {
            $container->setParameter("warble_media_phoenix.forms.{$key}.name", $form['name']);
            $container->setParameter("warble_media_phoenix.forms.{$key}.type", $form['type']);
            if (array_key_exists('validation_groups', $form)) {
                $container->setParameter("warble_media_phoenix.forms.{$key}.validation_groups", $form['validation_groups']);
            }
        }

        $container->setParameter('warble_media_phoenix.resetting.token_ttl', $config['resetting']['token_ttl']);

        $planManager = $container->findDefinition('warble_media_phoenix.billing.plan_manager');
        foreach ($config['billing']['plans'] as $id => $plan) {
            $planManager->addMethodCall('addPlan', [$id, $plan['name'], $plan]);
        }
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
