<?php

namespace Paloma\ShopBundle\Tests;

use Paloma\ShopBundle\PalomaShopBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new PalomaShopBundle(),
        ];
    }

    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__ . '/../Resources/config/routes/all.yaml');

        $routes->add('/', 'Paloma\ShopBundle\Tests\TestKernel::noopController', 'index');
        $routes->add('/register/confirm', 'Paloma\ShopBundle\Tests\TestKernel::noopController', 'paloma_register_confirm');
        $routes->add('/password_reset/confirm', 'Paloma\ShopBundle\Tests\TestKernel::noopController', 'paloma_password_reset_confirm');
    }

    public function noopController()
    {
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', [
            'test' => true,
            'secret' => 'test',
            'session' => [
                'storage_factory_id' => 'session.storage.factory.mock_file',
            ],
            'validation' => [
                'mapping' => [
                    'paths' => [
                        __DIR__ . '/../vendor/paloma/shop-client/src/Paloma/Shop/Resources/validation.yaml'
                    ]
                ],
            ],
        ]);

        $loader->load(__DIR__ . '/Resources/config/paloma.yaml');

        $loader->load(__DIR__ . '/Resources/config/services.yaml');

        $loader->load(__DIR__ . '/Resources/config/security.yaml');
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../cache/' . spl_object_hash($this);
    }
}