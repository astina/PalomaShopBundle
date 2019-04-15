<?php

namespace Paloma\ShopBundle\Tests\Controller;

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
            ['content-type' => 'application/json'],
            '{ "sku": "5881388", "quantity": 1 }'
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
            ['content-type' => 'application/json'],
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
}