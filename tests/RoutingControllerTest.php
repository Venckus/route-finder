<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class RoutingControllerTest extends ApiTestCase
{
    public function testRouteExist(): void
    {
        static::createClient()->request('GET', '/routing/ita/gbr');

        $this->assertResponseStatusCodeSame(400);
    }

    /**
     * @dataProvider routingUrlArgumentsProvider
     */
    public function testRoutingEndpointValidation(mixed $origin, mixed $destination, int $responseStatusCode): void
    {
        static::createClient()->request('GET', "/routing/{$origin}/{$destination}");

        $this->assertResponseStatusCodeSame($responseStatusCode);
    }

    /**
     * @return string[][]|int[][]
     */
    public function routingUrlArgumentsProvider(): array
    {
        return [
            'wrong origin' => ['it', 'ita', 422],
            'wrong destination' => ['ita', 'it', 422],
            'wrong origin and destination' => ['it', 'de', 422],
            'correct request' => ['ita', 'deu', 200],
        ];
    }
}
