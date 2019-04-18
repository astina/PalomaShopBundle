<?php

namespace Paloma\ShopBundle\Tests\Controller;

use Paloma\ShopBundle\Tests\FunctionalTest;

class CheckoutResourceTest extends FunctionalTest
{
    public function testSetAddresses()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/addresses',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{ 
                "billing": {
                    "title": "mr",
                    "firstName": "Hans",
                    "lastName": "Muster",
                    "country": "CH"
                },
                "shipping": null
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $order = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($order['billing']['address']);
        $this->assertEquals('Muster', $order['billing']['address']['lastName']);

        $this->assertNotNull($order['shipping']['address']);
        $this->assertEquals('Muster', $order['shipping']['address']['lastName']);
    }

    public function testListShippingMethods()
    {
        $client = static::createClient();

        $client->request('GET', '/api/checkout/shipping_methods/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $methods = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($methods);
    }

    public function testGetListShippingMethodOptions()
    {
        $client = static::createClient();

        $client->request('GET', '/api/checkout/shipping_methods/options?method=default');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $methods = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($methods);
    }

    public function testSetShippingMethod()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/shipping_methods/set',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{ "method": "unknown" }'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testListPaymentMethods()
    {
        $client = static::createClient();

        $client->request('GET', '/api/checkout/payment_methods/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $methods = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($methods);
    }

    public function testSetPaymentMethod()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/payment_methods/set',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{ "method": "unknown" }'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testInitializePayment()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/payments/initialize',
            [],
            [],
            ['HTTP_CONTENT_TYPE' => 'application/json'],
            '{ 
                "successUrl": "https://success",
                "errorUrl": "https://error",
                "cancelUrl": "https://cancel"
             }'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPurchase()
    {
        $client = static::createClient();

        $client->request('POST', '/api/checkout/purchase');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}