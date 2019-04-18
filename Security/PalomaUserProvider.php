<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PalomaUserProvider implements UserProviderInterface, \Paloma\Shop\Security\UserProviderInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    function getUser(): ?UserDetailsInterface
    {
        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof PalomaUser) {
            return $user->getDetails();
        }

        return null;
    }

    public function loadUserByUsername($username)
    {
        // Actual user will be loaded when checking credentials

        return new PalomaUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class instanceof PalomaUser;
    }
}