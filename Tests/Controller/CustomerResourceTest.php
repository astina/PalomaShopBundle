<?php

namespace Paloma\ShopBundle\Tests\Controller;

use Paloma\ShopBundle\Tests\FunctionalTest;

class CustomerResourceTest extends FunctionalTest
{
    public function testGetCustomer()
    {
        $client = static::createClient();

        $client->request('GET', '/api/customer/get');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testRegister()
    {
        $client = static::createClient();

        $emailAddress = 'test+' . time() . '@astina.io';

        $client->request(
            'POST',
            '/api/customer/register',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{
                "emailAddress": "' . $emailAddress . '",
                "password": "' . uniqid() . '"
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $customer = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($customer);
        $this->assertEquals($emailAddress, $customer['emailAddress']);
    }

    public function testRegisterEmailAddressExists()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/customer/register',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{
                "emailAddress": "test@astina.io",
                "password": "password"
            }'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $validation = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($validation['errors']);
        $this->assertEquals('emailAddress', $validation['errors'][0]['property']);
    }

    public function testUpdate()
    {
        $client = static::createAuthorizedClient('test@astina.io', 'password');

        $client->request(
            'POST',
            '/api/customer/update',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{
                "emailAddress": "test@astina.io",
                "firstName": "Hans",
                "lastName": "Muster"
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testUpdateUnauthorized()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/customer/update',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{
                "emailAddress": "test@astina.io",
                "firstName": "Hans"
            }'
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testUpdateAddress()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/customer/addresses/update',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{
                "addressType": "billing",
                "firstName": "Hans"
            }'
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}