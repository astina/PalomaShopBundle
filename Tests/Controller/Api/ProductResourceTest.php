<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class ProductResourceTest extends FunctionalTest
{
    public function testGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/get?itemNumber=INTEGRATION_TEST');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $category = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('INTEGRATION_TEST', $category['itemNumber']);
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

        $client->request('GET', '/api/products/similar?itemNumber=INTEGRATION_TEST');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRecommended()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/recommended?itemNumber=INTEGRATION_TEST');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}