<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaConfigInterface;

class PalomaConfig implements PalomaConfigInterface
{
    private $registrationConfirmationBaseUrl;

    private $passwordResetConfirmationBaseUrl;

    public function __construct(string $registrationConfirmationBaseUrl, string $passwordResetConfirmationBaseUrl)
    {
        $this->registrationConfirmationBaseUrl = $registrationConfirmationBaseUrl;
        $this->passwordResetConfirmationBaseUrl = $passwordResetConfirmationBaseUrl;
    }

    function getRegistrationConfirmationBaseUrl(): string
    {
        return $this->registrationConfirmationBaseUrl;
    }

    function getPasswordResetConfirmationBaseUrl(): string
    {
        return $this->passwordResetConfirmationBaseUrl;
    }
}