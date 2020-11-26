<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class CartResourceTest extends FunctionalTest
{
    public function testGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/cart');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($results['items']);
        $this->assertEquals([], $results['items']);
    }

    public function testAddItem()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/cart/items/add',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "sku": "INTEGRATION_TEST", "quantity": 1 }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $cart = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($cart['items']);
        $this->assertEquals(1, count($cart['items']));
    }

    public function testUpdateItem()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/cart/items/update',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "itemId": "123", "quantity": 2 }'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testRemoveItem()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/api/cart/items/remove?itemId=123'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRecommendations()
    {
        $client = static::createClient();

        $client->request('GET', '/api/cart/recommendations');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRepeatOrder()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/cart/repeat_order',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "orderNumber": "123" }'
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}