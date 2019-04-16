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

        $client->request(
            'POST',
            '/api/customer/register',
            [],
            [],
            ['content-type' => 'application/json'],
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
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/customer/update',
            [],
            [],
            ['content-type' => 'application/json'],
            '{
                "emailAddress": "test@astina.io",
                "firstName": "Hans"
            }'
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}