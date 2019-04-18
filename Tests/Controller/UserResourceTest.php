<?php

namespace Paloma\ShopBundle\Tests\Controller;

use Paloma\ShopBundle\Tests\FunctionalTest;

class UserResourceTest extends FunctionalTest
{
    public function testUpdatePassword()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/user/password/update',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "currentPassword": "invalid", "newPassword": "newpassword" }'
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testStartPasswordReset()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/user/password/reset/start',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "emailAddress": "test@astina.io" }'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testStartPasswordResetWithUnknownUser()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/user/password/reset/start',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "emailAddress": "test+unknown@astina.io" }'
        );

        $this->assertEquals(
            204,
            $client->getResponse()->getStatusCode(),
            'Should not advertise user non-existence');
    }

    public function testExistsPasswordReset()
    {
        $client = static::createClient();

        $client->request('GET', '/api/user/password/reset/exists?token=invalid');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['exists']);
    }

    public function testCompletePasswordReset()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/user/password/reset/complete',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "token": "invalid", "newPassword": "newpassword" }'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}