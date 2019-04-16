<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\UserDetailsInterface;
use Paloma\Shop\Customers\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultUserProvider implements UserProviderInterface
{
    private $security;

    public function __construct(TokenStorageInterface $security)
    {
        $this->security = $security;
    }

    function getUser(): ?UserDetailsInterface
    {
        // TODO: Implement getUser() method.
        return null;
    }
}