<?php

namespace Paloma\ShopBundle\Tests;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Customers\UserDetailsInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class FunctionalTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    protected static function createAuthorizedClient($username, $password)
    {
        $client = static::createClient();

        // TODO

        return $client;
    }

    protected function authenticateUser($username, $password): UserDetailsInterface
    {
        /** @var CustomersInterface $customers */
        $customers = static::$container->get('paloma_shop.customers');

        return $customers->authenticate($username, $password);
    }
}