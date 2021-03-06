<?php

namespace WarbleMedia\PhoenixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use WarbleMedia\PhoenixBundle\Form\ChangePasswordFormType;
use WarbleMedia\PhoenixBundle\Form\PaymentMethodType;
use WarbleMedia\PhoenixBundle\Form\ProfileFormType;
use WarbleMedia\PhoenixBundle\Form\ProfilePhotoFormType;
use WarbleMedia\PhoenixBundle\Form\RegistrationFormType;
use WarbleMedia\PhoenixBundle\Form\ResettingFormType;
use WarbleMedia\PhoenixBundle\Form\SubscriptionType;

class Configuration implements ConfigurationInterface
{
    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('warble_media_phoenix');

        $rootNode
            ->fixXmlConfig('developer_email')
            ->children()
                ->scalarNode('default_locale')
                    ->cannotBeEmpty()
                    ->defaultValue('en')
                ->end()
                ->scalarNode('firewall_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('product_name')
                    ->defaultValue('Phoenix')
                    ->info('The name of your application used in pdf invoices')
                ->end()
                ->arrayNode('vendor_details')
                    ->addDefaultsIfNotSet()
                    ->info('The details of your company used in pdf invoices')
                    ->children()
                        ->scalarNode('name')->defaultNull()->end()
                        ->scalarNode('street')->defaultNull()->end()
                        ->scalarNode('location')->defaultNull()->end()
                        ->scalarNode('phone')->defaultNull()->end()
                        ->scalarNode('email')->defaultNull()->end()
                        ->scalarNode('url')->defaultNull()->end()
                    ->end()
                ->end()
                ->integerNode('trial_days')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($v) { return $v < 0; })
                        ->thenInvalid('Trial days must be not be less than 0')
                    ->end()
                ->end()
                ->scalarNode('support_email_address')
                    ->validate()
                        ->ifTrue(function ($v) { return !filter_var($v, FILTER_VALIDATE_EMAIL); })
                        ->thenInvalid('Invalid support email address %s')
                    ->end()
                ->end()
                ->arrayNode('developer_emails')
                    ->canBeUnset()
                    ->prototype('scalar')
                        ->validate()
                            ->ifTrue(function ($v) { return !filter_var($v, FILTER_VALIDATE_EMAIL); })
                            ->thenInvalid('Invalid developer email address %s')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('profile_photos')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_url')->defaultValue('/profile_photos')->end()
                        ->scalarNode('base_path')->defaultValue('%kernel.root_dir%/../web/profile_photos')->end()
                    ->end()
                ->end()
                ->arrayNode('stripe')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->children()
                        ->scalarNode('secret_key')->end()
                        ->scalarNode('publishable_key')->end()
                    ->end()
                ->end()
                ->arrayNode('resetting')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('token_ttl')->defaultValue(86400)->end()
                    ->end()
                ->end()
            ->end()
            ->append($this->addModelsSection())
            ->append($this->addServicesSection())
            ->append($this->addFormsSection())
            ->append($this->addBillingSection())
        ;

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addModelsSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('models');

        $node
            ->isRequired()
            ->children()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('customer_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('subscription_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('invoice_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('metrics_class')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addServicesSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('services');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('user_manager')->defaultValue('warble_media_phoenix.model.user_manager.default')->end()
                ->scalarNode('user_photo_manager')->defaultValue('warble_media_phoenix.model.user_photo_manager.default')->end()
                ->scalarNode('customer_manager')->defaultValue('warble_media_phoenix.model.customer_manager.default')->end()
                ->scalarNode('subscription_manager')->defaultValue('warble_media_phoenix.model.subscription_manager.default')->end()
                ->scalarNode('invoice_manager')->defaultValue('warble_media_phoenix.model.invoice_manager.default')->end()
                ->scalarNode('metrics_manager')->defaultValue('warble_media_phoenix.model.metrics_manager.default')->end()
                ->scalarNode('indicators')->defaultValue('warble_media_phoenix.performance.indicators.default')->end()
                ->scalarNode('registration_manager')->defaultValue('warble_media_phoenix.security.registration_manager.default')->end()
                ->scalarNode('login_manager')->defaultValue('warble_media_phoenix.security.login_manager.default')->end()
                ->scalarNode('mailer')->defaultValue('warble_media_phoenix.mailer.default')->end()
                ->scalarNode('plan_manager')->defaultValue('warble_media_phoenix.billing.plan_manager.default')->end()
                ->scalarNode('payment_processor')->defaultValue('warble_media_phoenix.billing.payment_processor.default')->end()
                ->scalarNode('token_generator')->defaultValue('warble_media_phoenix.util.token_generator.default')->end()
            ->end()
        ;

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addFormsSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('forms');

        $node
            ->canBeUnset()
            ->addDefaultsIfNotSet()
            ->children()
                ->append($this->addFormsChildSection('registration', RegistrationFormType::class, ['Registration', 'Default']))
                ->append($this->addFormsChildSection('resetting', ResettingFormType::class, ['Resetting', 'Default']))
                ->append($this->addFormsChildSection('profile', ProfileFormType::class, ['Profile', 'Default']))
                ->append($this->addFormsChildSection('profile_photo', ProfilePhotoFormType::class))
                ->append($this->addFormsChildSection('change_password', ChangePasswordFormType::class, ['ChangePassword', 'Default']))
                ->append($this->addFormsChildSection('subscription', SubscriptionType::class))
                ->append($this->addFormsChildSection('payment_method', PaymentMethodType::class))
            ->end()
        ;

        return $node;
    }

    /**
     * @param string $name
     * @param string $default
     * @param array  $validationGroups
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addFormsChildSection($name, $default, array $validationGroups = null)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($name);

        $node
            ->canBeUnset()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('name')->defaultValue("warble_media_phoenix_{$name}_type")->end()
                ->scalarNode('type')->defaultValue($default)->end()
            ->end()
        ;

        if ($validationGroups !== null) {
            $node
                ->children()
                    ->scalarNode('validation_groups')->defaultValue($validationGroups)->end()
                ->end()
            ;
        }

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addBillingSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('billing');

        $node
            ->fixXmlConfig('plan')
            ->children()
                ->scalarNode('currency')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('3-letter ISO 4217 currency code')
                ->end()
                ->booleanNode('prorate_plan_upgrades')
                    ->defaultTrue()
                ->end()
                ->arrayNode('plans')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) { return ['name' => $v]; })
                        ->end()
                        ->children()
                            ->scalarNode('name')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->floatNode('price')
                                ->defaultValue(0)
                                ->validate()
                                    ->ifTrue(function ($v) { return $v < 0; })
                                    ->thenInvalid('Invalid price %f must be greater than or equal to 0.')
                                ->end()
                            ->end()
                            ->enumNode('interval')
                                ->values(['monthly', 'yearly'])
                                ->defaultValue('monthly')
                            ->end()
                            ->integerNode('trial_days')
                                ->defaultValue(0)
                            ->end()
                            ->booleanNode('active')
                                ->defaultTrue()
                            ->end()
                            ->arrayNode('features')
                                ->canBeUnset()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
