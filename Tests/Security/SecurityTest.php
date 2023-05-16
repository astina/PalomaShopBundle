<?php

namespace Paloma\ShopBundle\Tests;

use Paloma\Shop\Security\PalomaSecurityInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityTest extends FunctionalTest
{
    public function testLoginFormAuthentication()
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
//        $tokenManager = static::getContainer()->get('security.csrf.token_manager');
//        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/security/login',
            [
                'username' => 'test@astina.io',
                'password' => 'password',
                '_csrf_token' => 'test', // $token->getValue(),
                '_remember_me' => '1'
            ]
        );

        $cookies = $client->getCookieJar();
        $this->assertNotNull($cookies->get('MOCKSESSID'));
        $this->assertNotNull($cookies->get('REMEMBERME'));

        /** @var PalomaSecurityInterface $palomaSecurity */
        $palomaSecurity = static::getContainer()->get('paloma_shop.security');

        $user = $palomaSecurity->getUser();

        $this->assertNotNull($user);
        $this->assertEquals('test@astina.io', $user->getUsername());
    }

    public function testHttpJsonAuthentication()
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
//        $tokenManager = static::getContainer()->get('security.csrf.token_manager');
//        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/api/user/authenticate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CSRF_TOKEN' => 'test', //$token->getValue(),
            ],
            '{ 
                "username": "test@astina.io", 
                "password": "password"
             }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        /** @var PalomaSecurityInterface $palomaSecurity */
        $palomaSecurity = static::getContainer()->get('paloma_shop.security');

        $user = $palomaSecurity->getUser();

        $this->assertNotNull($user);
        $this->assertEquals('test@astina.io', $user->getUsername());
    }

    public function testHttpJsonAuthenticationWithBadCredentials()
    {
        $client = static::createClient();

        /** @var CsrfTokenManagerInterface $tokenManager */
//        $tokenManager = static::getContainer()->get('security.csrf.token_manager');
//        $token = $tokenManager->getToken('authenticate');

        $client->request(
            'POST',
            '/api/user/authenticate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CSRF_TOKEN' => 'test', //$token->getValue(),
            ],
            '{ 
                "username": "test@astina.io", 
                "password": "invalid"
             }'
        );

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        /** @var PalomaSecurityInterface $palomaSecurity */
        $palomaSecurity = static::getContainer()->get('paloma_shop.security');

        $user = $palomaSecurity->getUser();

        $this->assertNull($user);
    }
}