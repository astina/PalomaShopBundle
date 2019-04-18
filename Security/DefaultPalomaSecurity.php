<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\UserDetailsInterface;
use Paloma\Shop\PalomaSecurityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultPalomaSecurity implements PalomaSecurityInterface
{
    private $security;

    public function __construct(TokenStorageInterface $security)
    {
        $this->security = $security;
    }

    function getUser(): ?UserDetailsInterface
    {
        $token = $this->security->getToken();
        if ($token === null) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof PalomaUser) {
            return $user->getDetails();
        }

        // TODO throw exception?

        return null;
    }

    function setAuthenticated(UserDetailsInterface $user): void
    {
        $token = new UsernamePasswordToken(
            $user,
            '',
            'paloma',
            [ 'ROLE_PALOMA_CUSTOMER' ]
        );

        $this->security->setToken($token);
    }
}