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
        $def->replaceArgument(1, $config);

        $def = $container->getDefinition('paloma_shop.channel_resolver');
        $def->replaceArgument(0, $config['channels']);

        $def = $container->getDefinition('paloma_shop.client_factory');
        $def->replaceArgument(1, $this->createClientFactoryOptions($config));

        // TODO only load controllers if needed (if default UI is used)
        $loader->load('services/controllers.yaml');

        $env = $container->getParameter('kernel.environment');
        if ('dev' === $env) {
            $loader->load('services/profiler.yaml');
        }
    }

    /**
     * @param array $config
     * @return array
     */
    private function createClientFactoryOptions(array $config)
    {
        $defaultChannel = null;
        $defaultCatalog = null;
        $defaultLocale = null;
        foreach ($config['channels'] as $name => $channel) {
            if ($channel['is_default']) {
                $defaultChannel = $name;
                $defaultCatalog = $channel['catalog'];
                $defaultLocale = count($channel['locales']) > 0 ? $channel['locales'][0] : null;
                break;
            }
        }

        return $config['client'] + [
                'channel' => $defaultChannel,
                'catalog' => $defaultCatalog,
                'locale' => $defaultLocale,
            ];
    }
}