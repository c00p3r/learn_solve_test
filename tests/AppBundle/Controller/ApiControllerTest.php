<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testChartData() {
        $client = static::createClient();

        $client->request('GET', 'api/chart_data');

        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());

        $json = json_decode($response->getContent(), true);

        $this->assertTrue(count($json) == 5);

        $this->assertArrayHasKey('exchange', $json[0]);
        $this->assertArrayHasKey('ticker', $json[0]);
        $this->assertArrayHasKey('change', $json[0]);
    }
}
