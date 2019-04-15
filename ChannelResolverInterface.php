<?php

namespace Paloma\ShopBundle;

use Symfony\Component\HttpFoundation\Request;

interface ChannelResolverInterface
{
    /**
     * Resolves the Paloma channel based on the current request.
     * Should fall back to a (configured) default channel.
     *
     * @param Request $request
     * @return string The Paloma channel code
     */
    function resolveChannel(Request $request): string;
}