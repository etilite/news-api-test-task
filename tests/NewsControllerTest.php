<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewsControllerTest extends WebTestCase
{
    public function testRootRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/news');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertStringContainsString("Новость 7", $data);
        $this->assertStringContainsString("Новость 3", $data);
        $this->assertStringContainsString("политика", $data);
        $this->assertStringContainsString("totalElements", $data);
    }
}