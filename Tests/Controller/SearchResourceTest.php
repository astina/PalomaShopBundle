<?php

namespace Paloma\ShopBundle\Tests\Controller;

use Paloma\ShopBundle\Tests\FunctionalTest;

class SearchResourceTest extends FunctionalTest
{
    public function testSearchPost()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/search',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ 
                "query": "test", 
                "filters": [ {"property": "master.pricing.grossPrice", "lessThan": "100.00"} ], 
                "includeFilterAggregates": true }
            '
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($results['content']);
        $this->assertTrue(count($results['filterAggregates']) > 0);
    }

    public function testSearchGet()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/search?query=test&category=foo'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($results['content']);
    }

    public function testSearchSuggestions()
    {
        $client = static::createClient();

        $client->request('GET', '/api/search/suggestions?query=test');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($results['categories']);
        $this->assertNotNull($results['products']);
    }
}