<?php

namespace Paloma\ShopBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PalomaUserProvider implements UserProviderInterface
{
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
        return PalomaUser::class === $class;
    }
}