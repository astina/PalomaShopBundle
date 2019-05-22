<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\Customer;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\PalomaClientInterface;
use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PalomaUserProvider implements UserProviderInterface, \Paloma\Shop\Security\UserProviderInterface
{
    private $tokenStorage;

    private $client;

    public function __construct(TokenStorageInterface $tokenStorage, PalomaClientInterface $client)
    {
        $this->tokenStorage = $tokenStorage;
        $this->client = $client;
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

    function getCustomer(): ?CustomerInterface
    {
        $user = $this->getUser();

        if ($user === null) {
            return null;
        }

        // TODO caching

        $data = $this->client->customers()->getCustomer($user->getCustomerId());

        return new Customer($data);
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