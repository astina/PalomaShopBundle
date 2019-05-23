<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\Customer;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\PalomaClientInterface;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PalomaSecurity implements PalomaSecurityInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var PalomaClientInterface
     */
    private $client;

    private $_cache = [
        'customers' => [],
    ];

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

        $customerId = $user->getCustomerId();

        if (isset($this->_cache['customers'][$customerId])) {
            return $this->_cache['customers'][$customerId];
        }

        $data = $this->client->customers()->getCustomer($customerId);

        $customer = new Customer($data);

        $this->_cache['customers'][$customerId] = $customer;

        return $customer;
    }
}