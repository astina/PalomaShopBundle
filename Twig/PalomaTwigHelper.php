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
        return $this->requestStack->getMasterRequest()->attributes->get('paloma.channel');
    }

    public function getLocales(): array
    {
        return $this->config['channels'][$this->getChannel()]['locales'];
    }

    public function getSupportEmailAddress()
    {
        return $this->config['channels'][$this->getChannel()]['support_mail'];
    }
}