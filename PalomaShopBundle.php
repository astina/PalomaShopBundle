<?php

namespace Paloma\ShopBundle;

use Paloma\ShopBundle\DependencyInjection\PalomaShopExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PalomaShopBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return PalomaShopExtension::class;
    }
}