<?php

namespace Paloma\ShopBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class FunctionalTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    protected static function createAuthorizedClient($username = 'test@astina.io', $password = 'password')
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
        $tokenManager = static::$container->get('security.csrf.token_manager');
        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/login',
            [
                'username' => $username,
                'password' => $password,
                '_csrf_token' => $token->getValue(),
            ]
        );

        return $client;
    }
}