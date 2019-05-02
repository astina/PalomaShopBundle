<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

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
            ['CONTENT_TYPE' => 'application/json'],
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
            ['CONTENT_TYPE' => 'application/json'],
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
        $client = static::createAuthorizedClient();

        $company = 'Example ' . rand(1000, 9999);

        $client->request(
            'POST',
            '/api/customer/update',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "emailAddress": "test@astina.io",
                "firstName": "Hans",
                "lastName": "Muster",
                "company": "' . $company . '",
                "locale": "de_CH",
                "gender": "male",
                "dateOfBirth": "1980-01-01"
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $customer = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($company, $customer['company']);
    }

    public function testUpdateUnauthorized()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/customer/update',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "emailAddress": "test@astina.io",
                "firstName": "Hans"
            }'
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testUpdateAddress()
    {
        $client = static::createAuthorizedClient();

        $street = 'Musterweg ' . rand(1000, 9999);

        $client->request(
            'POST',
            '/api/customer/addresses/update',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "addressType": "billing",
                "firstName": "Hans",
                "lastName": "Muster",
                "street": "'. $street . '",
                "zipCode": "8000",
                "city": "Zürich",
                "country": "CH"
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $address = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($street, $address['street']);
    }
}