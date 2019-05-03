<?php

namespace Paloma\ShopBundle;

use Symfony\Component\HttpFoundation\Request;

class DefaultChannelResolver implements ChannelResolverInterface
{
    private $defaultChannel;

    public function __construct(array $channels)
    {
        $this->defaultChannel = array_keys($channels)[0];
        foreach ($channels as $name => $channel) {
            if ($channel['is_default']) {
                $this->defaultChannel = $name;
                break;
            }
        }
    }

    function resolveChannel(Request $request): string
    {
        return $this->defaultChannel;
    }
}