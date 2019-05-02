<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class OrderResourceTest extends FunctionalTest
{
    public function testList()
    {
        $client = static::createAuthorizedClient();

        $client->request('GET', '/api/orders/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGet()
    {
        $client = static::createAuthorizedClient();

        $client->request('GET', '/api/orders/get?orderNumber=123');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testLatest()
    {
        $client = static::createAuthorizedClient();

        $client->request('GET', '/api/orders/latest');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}