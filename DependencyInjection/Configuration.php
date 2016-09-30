<?php

namespace WarbleMedia\PhoenixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use WarbleMedia\PhoenixBundle\Form\RegistrationFormType;

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
            ->children()
                ->scalarNode('firewall_name')->isRequired()->cannotBeEmpty()->end()
            ->end();
        ;

        $this->addModelsSection($rootNode);
        $this->addFormsSection($rootNode);

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
                    ->end()
                ->end()
            ->end()
        ;
    }
}
