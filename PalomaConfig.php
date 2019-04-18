<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaConfigInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PalomaConfig implements PalomaConfigInterface
{
    private $registrationConfirmationRoute;

    private $passwordResetConfirmationRoute;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        string $registrationConfirmationRoute,
        string $passwordResetConfirmationRoute)
    {
        $this->registrationConfirmationRoute = $registrationConfirmationRoute;
        $this->passwordResetConfirmationRoute = $passwordResetConfirmationRoute;
        $this->urlGenerator = $urlGenerator;
    }

    function getRegistrationConfirmationBaseUrl(): string
    {
        return $this->urlGenerator->generate($this->registrationConfirmationRoute);
    }

    function getPasswordResetConfirmationBaseUrl(): string
    {
        return $this->urlGenerator->generate($this->passwordResetConfirmationRoute);
    }
}