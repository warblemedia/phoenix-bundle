<?php

namespace WarbleMedia\PhoenixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
                ->integerNode('trial_days')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($v) { return $v < 0; })
                        ->thenInvalid('Trial days must be greater than 0')
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
            ->end();
        ;

        $this->addModelsSection($rootNode);
        $this->addServicesSection($rootNode);
        $this->addFormsSection($rootNode);
        $this->addResettingSection($rootNode);
        $this->addBillingSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addModelsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('models')
                    ->isRequired()
                    ->children()
                        ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('customer_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('subscription_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('invoice_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('metrics_class')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addServicesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('services')
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
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addFormsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('forms')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('registration')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_registration_type')->end()
                                ->scalarNode('type')->defaultValue(RegistrationFormType::class)->end()
                                ->scalarNode('validation_groups')->defaultValue(['Registration', 'Default'])->end()
                            ->end()
                        ->end()
                        ->arrayNode('resetting')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_resetting_type')->end()
                                ->scalarNode('type')->defaultValue(ResettingFormType::class)->end()
                                ->scalarNode('validation_groups')->defaultValue(['Resetting', 'Default'])->end()
                            ->end()
                        ->end()
                        ->arrayNode('profile')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_profile_type')->end()
                                ->scalarNode('type')->defaultValue(ProfileFormType::class)->end()
                                ->scalarNode('validation_groups')->defaultValue(['Profile', 'Default'])->end()
                            ->end()
                        ->end()
                        ->arrayNode('profile_photo')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_profile_photo_type')->end()
                                ->scalarNode('type')->defaultValue(ProfilePhotoFormType::class)->end()
                            ->end()
                        ->end()
                        ->arrayNode('change_password')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_change_password_type')->end()
                                ->scalarNode('type')->defaultValue(ChangePasswordFormType::class)->end()
                                ->scalarNode('validation_groups')->defaultValue(['ChangePassword', 'Default'])->end()
                            ->end()
                        ->end()
                        ->arrayNode('subscription')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_subscription_type')->end()
                                ->scalarNode('type')->defaultValue(SubscriptionType::class)->end()
                            ->end()
                        ->end()
                        ->arrayNode('payment_method')
                            ->canBeUnset()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('warble_media_phoenix_payment_method_type')->end()
                                ->scalarNode('type')->defaultValue(PaymentMethodType::class)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addResettingSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resetting')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('token_ttl')->defaultValue(86400)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBillingSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('billing')
                    ->fixXmlConfig('plan')
                    ->children()
                        ->scalarNode('currency')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('3-letter ISO 4217 currency code')
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
                ->end()
            ->end()
        ;
    }
}
