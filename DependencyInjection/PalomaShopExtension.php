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

        $loader->load('services/core.yaml');

        $def = $container->getDefinition('paloma_shop.config');
        $def->replaceArgument(0, $config);

        $def = $container->getDefinition('paloma_shop.twig_helper');
        $def->replaceArgument(3, $config);

        $def = $container->getDefinition('paloma_shop.channel_resolver');
        $def->replaceArgument(0, $config['channels']);

        $def = $container->getDefinition('paloma_shop.client_factory');
        $def->replaceArgument(3, $config['client']);

        // TODO only load controllers if needed (if default UI is used)
        $loader->load('services/controllers.yaml');

        $env = $container->getParameter('kernel.environment');
        if ('dev' === $env) {
            $loader->load('services/profiler.yaml');
        }
    }
}