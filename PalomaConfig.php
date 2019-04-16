<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaConfigInterface;

class PalomaConfig implements PalomaConfigInterface
{
    private $registrationConfirmationBaseUrl;

    public function __construct(string $registrationConfirmationBaseUrl)
    {
        $this->registrationConfirmationBaseUrl = $registrationConfirmationBaseUrl;
    }

    function getRegistrationConfirmationBaseUrl(): string
    {
        return $this->registrationConfirmationBaseUrl;
    }
}