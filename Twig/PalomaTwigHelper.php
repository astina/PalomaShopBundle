<?php

namespace Paloma\ShopBundle\Twig;

use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Paloma\ShopBundle\Serializer\SerializationConstants;
use Symfony\Component\HttpFoundation\RequestStack;

class PalomaTwigHelper
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $config;
    /**
     * @var PalomaSecurityInterface
     */
    private $security;
    /**
     * @var PalomaSerializer
     */
    private $serializer;

    public function __construct(RequestStack $requestStack, PalomaSecurityInterface $security, PalomaSerializer $serializer, array $config)
    {
        $this->requestStack = $requestStack;
        $this->config = $config;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    public function getChannel(): string
    {
        return $this->requestStack->getMasterRequest()->attributes->get('paloma.channel');
    }

    public function getLocales(): array
    {
        return $this->config['channels'][$this->getChannel()]['locales'];
    }

    public function getUserJson()
    {
        $user = $this->security->getUser();

        return $this->serializer->serialize($user, SerializationConstants::OPTIONS_USER);
    }
}