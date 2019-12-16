<?php

namespace Paloma\ShopBundle\Sitemap;

use Paloma\Shop\Common\PricingContext;
use Paloma\Shop\Common\PricingContextProviderInterface;

class GoogleSitemapPricingContextProvider implements PricingContextProviderInterface
{
    function provide(): PricingContext
    {
        return new PricingContext();
    }
}