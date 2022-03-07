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

    /**
     * @dataProvider provideDataForBadRequest
     * @param $requestParams
     * @param $errorMessage
     */
    public function testError(array $requestParams, string $errorMessage): void
    {
        $client = static::createClient();
        $client->request('GET', '/news', $requestParams);

        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $data = $response->getContent();
        $this->assertStringContainsString($errorMessage, $data);
    }

    /**
     * @return \Generator
     */
    public function provideDataForBadRequest(): iterable
    {
        yield ['requestParams' => ['year' => '2022', 'month' => ''], 'errorMessage' => "Set both year and month to filter by date: year=\\\"2022\\\", month=\\\"\\\" was provided"];
        yield ['requestParams' => ['year' => '', 'month' => '1'], 'errorMessage' => "Set both year and month to filter by date: year=\\\"\\\", month=\\\"1\\\" was provided"];
        yield ['requestParams' => ['year' => 'abc', 'month' => '1'], 'errorMessage' => "Unsupported format of date: year=\\\"abc\\\", month=\\\"1\\\" was provided"];
        yield ['requestParams' => ['year' => '2022', 'month' => '13'], 'errorMessage' => "Unsupported format of date: year=\\\"2022\\\", month=\\\"13\\\" was provided"];
        yield ['requestParams' => ['year' => '1', 'month' => '13'], 'errorMessage' => "Unsupported format of date: year=\\\"1\\\", month=\\\"13\\\" was provided"];
    }
}