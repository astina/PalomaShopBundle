<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class AddressResourceTest extends FunctionalTest
{
    public function testValidateValid()
    {
        $client = static::createClient();

        $client->request('POST',
            '/api/address/validate',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "firstName": "Hans", "lastName": "Muster", "street": "Musterweg 1", "zipCode": "8000", "city": "Zurich", "country": "CH" }');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testValidateEmpty()
    {
        $client = static::createClient();

        $client->request('POST',
            '/api/address/validate',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ }');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $validation = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($validation['valid']);
        $this->assertEquals(6, count($validation['errors']));
    }

    public function testValidateInvalidCountry()
    {
        $client = static::createClient();

        $client->request('POST',
            '/api/address/validate',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "firstName": "Hans", "lastName": "Muster", "street": "Musterweg 1", "zipCode": "8000", "city": "Zurich", "country": "CHE" }');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $validation = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($validation['valid']);
        $this->assertEquals(1, count($validation['errors']));
    }
}