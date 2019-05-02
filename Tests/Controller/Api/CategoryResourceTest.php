<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class CategoryResourceTest extends FunctionalTest
{
    public function testList()
    {
        $client = static::createClient();

        $client->request('GET', '/api/categories/list?depth=1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $categories = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($categories[0]['subCategories']);
    }

    public function testGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/categories/get?code=p_cats&depth=1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $category = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('p_cats', $category['code']);
        $this->assertNotNull($category['subCategories']);
    }

    public function testGetNoCode()
    {
        $client = static::createClient();

        $client->request('GET', '/api/categories/get?code=');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}