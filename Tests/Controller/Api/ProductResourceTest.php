<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class ProductResourceTest extends FunctionalTest
{
    public function testGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/get?itemNumber=6303640');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $category = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('6303640', $category['itemNumber']);
    }

    public function testGetNoItemNumber()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/get?itemNumber=');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testGetNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/get?itemNumber=non_existing');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testSimilar()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/similar?itemNumber=6303640');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRecommended()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/recommended?itemNumber=6303640');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}