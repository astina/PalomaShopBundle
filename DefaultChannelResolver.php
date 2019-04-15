<?php

namespace Paloma\ShopBundle;

use Symfony\Component\HttpFoundation\Request;

class DefaultChannelResolver implements ChannelResolverInterface
{
    private $defaultChannel;

    public function __construct(string $defaultChannel)
    {
        $this->defaultChannel = $defaultChannel;
    }

    function resolveChannel(Request $request): string
    {
        return $this->defaultChannel;
    }
}