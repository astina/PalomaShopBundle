<?php

namespace Paloma\ShopBundle\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class FunctionalTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createAuthorizedClient($username = 'test@astina.io', $password = 'password'): KernelBrowser
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $csrfToken = $crawler->filter('meta[data-csrf-token]')
            ->getNode(0)
            ->attributes['data-csrf-token']
            ->value;

        $client->request(
            'POST',
            '/security/login',
            [
                'username' => $username,
                'password' => $password,
                '_csrf_token' => $csrfToken,
            ]
        );

        return $client;
    }
}