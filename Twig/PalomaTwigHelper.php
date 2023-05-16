<?php

namespace Paloma\ShopBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class PalomaTwigHelper
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $config;

    public function __construct(RequestStack $requestStack, array $config)
    {
        $this->requestStack = $requestStack;
        $this->config = $config;
    }

    public function getChannel(): string
    {
        $channel = $this->requestStack->getMainRequest()->attributes->get('paloma.channel');

        return $channel ?: $this->getDefaultChannel();
    }

    public function getLocales(): array
    {
        return $this->config['channels'][$this->getChannel()]['locales'];
    }

    public function getSupportEmailAddress()
    {
        return $this->config['channels'][$this->getChannel()]['support_mail'];
    }

    private function getDefaultChannel()
    {
        foreach ($this->config['channels'] as $name => $channel) {
            if ($channel['is_default']) {
                return $name;
            }
        }

        foreach ($this->config['channels'] as $name => $channel) {
            return $name;
        }
    }
}