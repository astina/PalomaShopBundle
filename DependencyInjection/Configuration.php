<?php

namespace Paloma\ShopBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('paloma_shop');

        $treeBuilder->getRootNode()
            ->children()

                ->arrayNode('client')
                    ->children()
                        ->scalarNode('base_url')->isRequired()->end()
                        ->scalarNode('api_key')->isRequired()->defaultValue('%env(PALOMA_API_KEY)%')->end()
                    ->end()
                ->end()

                ->arrayNode('channels')
                    ->useAttributeAsKey('name')
                    ->addDefaultChildrenIfNoneSet([
                        'name' => 'default',
                        'is_default' => true,
                    ])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('is_default')->defaultFalse()->end()
                            ->arrayNode('locales')
                                ->scalarPrototype()->defaultValue(['de', 'en'])->end()
                            ->end()
                            ->scalarNode('support_mail')->defaultValue('support@astina.ch')->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('urls')
                    ->addDefaultsIfNotSet()
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('confirm_registration')
                                ->info('Route name of the registration confirmation page')
                                ->isRequired()
                                ->defaultValue('paloma_customer_register_confirm')
                            ->end()
                            ->scalarNode('confirm_password_reset')
                                ->info('Route name of the password reset confirmation page')
                                ->isRequired()
                                ->defaultValue('paloma_security_password_reset_confirm')
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}