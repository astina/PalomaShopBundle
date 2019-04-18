<?php

namespace Paloma\ShopBundle\Tests;

use Paloma\Shop\Security\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityTest extends FunctionalTest
{
    public function testLoginFormAuthentication()
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
        $tokenManager = static::$container->get('security.csrf.token_manager');
        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/login',
            [
                'username' => 'test@astina.io',
                'password' => 'password',
                '_csrf_token' => $token->getValue(),
                '_remember_me' => '1'
            ]
        );

        $cookies = $client->getCookieJar();
        $this->assertNotNull($cookies->get('MOCKSESSID'));
        $this->assertNotNull($cookies->get('REMEMBERME'));

        /** @var UserProviderInterface $userProvider */
        $userProvider = static::$container->get('paloma_shop.security.user_provider');

        $user = $userProvider->getUser();

        $this->assertNotNull($user);
        $this->assertEquals('test@astina.io', $user->getUsername());
    }

    public function testHttpJsonAuthentication()
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
        $tokenManager = static::$container->get('security.csrf.token_manager');
        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/api/user/authenticate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CSRF_TOKEN' => $token->getValue(),
            ],
            '{ 
                "username": "test@astina.io", 
                "password": "password"
             }'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        /** @var UserProviderInterface $userProvider */
        $userProvider = static::$container->get('paloma_shop.security.user_provider');

        $user = $userProvider->getUser();

        $this->assertNotNull($user);
        $this->assertEquals('test@astina.io', $user->getUsername());
    }

    public function testHttpJsonAuthenticationWithBadCredentials()
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
        $tokenManager = static::$container->get('security.csrf.token_manager');
        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/api/user/authenticate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CSRF_TOKEN' => $token->getValue(),
            ],
            '{ 
                "username": "test@astina.io", 
                "password": "invalid"
             }'
        );

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        /** @var UserProviderInterface $userProvider */
        $userProvider = static::$container->get('paloma_shop.security.user_provider');

        $user = $userProvider->getUser();

        $this->assertNull($user);
    }
}