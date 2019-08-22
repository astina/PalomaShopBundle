<?php

namespace Paloma\ShopBundle\Security;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Paloma\Shop\Customers\Customer;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\PalomaClientInterface;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

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

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RequestStack
     */
    private $requestStack;

    private $_cache = [
        'customers' => [],
    ];

    public function __construct(TokenStorageInterface $tokenStorage,
                                PalomaClientInterface $client,
                                EventDispatcherInterface $eventDispatcher,
                                RequestStack $requestStack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;
        $this->requestStack = $requestStack;
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

    function setUser(UserDetailsInterface $userDetails): void
    {
        $user = new PalomaUser($userDetails->getUsername());
        $user->setDetails($userDetails);

        $token = new UsernamePasswordToken(
            $user,
            '',
            'paloma',
            $user->getRoles()
        );

        $this->tokenStorage->setToken($token);

        unset($this->_cache['customers'][$userDetails->getCustomerId()]);

        $event = new InteractiveLoginEvent($this->requestStack->getMasterRequest(), $token);
        $this->eventDispatcher->dispatch($event);
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

        try {
            $data = $this->client->customers()->getCustomer($customerId);
        } catch (ServerException $e) {
            throw BackendUnavailable::ofException($e);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {

                $this->tokenStorage->setToken(null);

                return null;
            }

            throw BackendUnavailable::ofException($e);
        }

        $customer = new Customer($data);

        $this->_cache['customers'][$customerId] = $customer;

        return $customer;
    }
}