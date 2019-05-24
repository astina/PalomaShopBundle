<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaConfigInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PalomaConfig implements PalomaConfigInterface
{
    private $config;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(array $config, UrlGeneratorInterface $urlGenerator)
    {
        $this->config = $config;
        $this->urlGenerator = $urlGenerator;
    }

    function getRegistrationConfirmationBaseUrl(): string
    {
        return $this->urlGenerator->generate(
            $this->config['urls']['confirm_registration'],
            [],
            UrlGeneratorInterface::ABSOLUTE_URL);
    }

    function getPasswordResetConfirmationBaseUrl(): string
    {
        return $this->urlGenerator->generate(
            $this->config['urls']['confirm_password_reset'],
            [],
            UrlGeneratorInterface::ABSOLUTE_URL);
    }
}