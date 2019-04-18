<?php

namespace Paloma\ShopBundle\Tests;

use Paloma\ShopBundle\PalomaShopBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new PalomaShopBundle(),
        ];
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import(__DIR__ . '/../Resources/config/routes.yaml');
        $routes->import(__DIR__ . '/../Resources/config/routes_api.yaml', '/api');

        $routes->add('/', null, 'index');
        $routes->add('/register/confirm', null, 'paloma_register_confirm');
        $routes->add('/password_reset/confirm', null, 'paloma_password_reset_confirm');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', [
            'test' => true,
            'secret' => 'test',
            'session' => [
                'storage_id' => 'session.storage.mock_file',
            ],
        ]);

        $loader->load(__DIR__ . '/Resources/config/paloma.yaml');

        $loader->load(__DIR__ . '/Resources/config/services.yaml');

        $loader->load(__DIR__ . '/Resources/config/security.yaml');
    }

    public function getCacheDir()
    {
        return __DIR__ . '/../cache/' . spl_object_hash($this);
    }
}