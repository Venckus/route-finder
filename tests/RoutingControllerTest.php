<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingControllerTest extends WebTestCase
{
    /**
     * @dataProvider routingUrlArgumentsProvider
     */
    public function testRoutingEndpointValidation(mixed $origin, mixed $destination, int $responseStatusCode): void
    {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $crawler = $client->request('GET', "/routing/{$origin}/{$destination}");

        $this->assertResponseStatusCodeSame($responseStatusCode, 'bilekas');
    }

    public function routingUrlArgumentsProvider(): array
    {
        return [
            'wrong origin' => ['wrong', 'pln', 422],
            'wrong destination' => ['pln', 'wrong', 422],
            'wrong origin and destination' => ['origin', 'destination', 422],
        ];
    }

    public function testRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/routing/');

        $this->assertResponseIsSuccessful();
    }
}
