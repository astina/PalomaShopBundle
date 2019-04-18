<?php

namespace Paloma\ShopBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PalomaShopExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');

        $def = $container->getDefinition('paloma_shop.config');
        $def->replaceArgument(1, $config['urls']['confirm_registration']);
        $def->replaceArgument(2, $config['urls']['confirm_password_reset']);

        $def = $container->getDefinition('paloma_shop.channel_resolver');
        $def->replaceArgument(0, $config['channels']['default']);

        $def = $container->getDefinition('paloma_shop.client_factory');
        $def->replaceArgument(2, $config['client']);

        $env = $container->getParameter('kernel.environment');
        if ('dev' === $env) {
            $loader->load('profiler.yaml');
        }
    }
}