<?php

namespace Paloma\ShopBundle\Tests\Controller;

use Paloma\ShopBundle\Tests\FunctionalTest;

class OrderResourceTest extends FunctionalTest
{
    public function testList()
    {
        $client = static::createClient();

        $client->request('GET', '/api/orders/list');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/orders/get?orderNumber=123');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testLatest()
    {
        $client = static::createClient();

        $client->request('GET', '/api/orders/latest');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}