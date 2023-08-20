<?php

namespace App\Tests;

use App\Services\RoutingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoutingServiceTest extends KernelTestCase
{
    /**
     * @dataProvider requestAndResultProvider
     * @param string[] $route
     */
    public function testFindRouteRecursive(
        string $origin,
        string $destination,
        ?array $expectedRoute
    ): void {
        self::bootKernel();
        $container = static::getContainer();
        $service   = $container->get(RoutingService::class);
        $service->setOrigin($origin)->setDestination($destination);

        $service->countries = [
            'ITA' => ['borders' => ['AUT', 'FRA']],
            'AUT' => ['borders' => ['CZE', 'DEU', 'ITA']],
            'FRA' => ['borders' => ['DEU', 'ITA']],
            'DEU' => ['borders' => ['AUT', 'FRA', 'POL']],
            'POL' => ['borders' => ['CZE', 'DEU']],
            'CZE' => ['borders' => ['DEU', 'POL']],
        ];

        $result = $service->findBreadthFirstPath();

        $this->assertSame($expectedRoute, $result);
    }

    /**
     * @return string[][][]
     */
    public function requestAndResultProvider(): array
    {
        return [
            'ITA -> ITA' => ['origin' => 'ITA', 'destination' => 'ITA', 'route' => ['ITA']],
            'ITA -> AUT' => ['origin' => 'ITA', 'destination' => 'AUT', 'route' => ['ITA', 'AUT']],
            'ITA -> DEU' => ['origin' => 'ITA', 'destination' => 'DEU', 'route' => ['ITA', 'AUT', 'DEU']],
            'FRA -> CZE' => ['origin' => 'FRA', 'destination' => 'CZE', 'route' => ['FRA', 'DEU', 'AUT', 'CZE']],
            'ITA -> POL' => ['origin' => 'ITA', 'destination' => 'POL', 'route' => ['ITA', 'AUT', 'CZE', 'POL']],
            'ITA -> GBR' => ['origin' => 'ITA', 'destination' => 'GBR', 'route' => null],
        ];
    }

    /**
     * @dataProvider unaccessibleCountriesDataProvider
     * @param string[][] $countries
     */
    public function testIsCountriesAccessibleValidation(array $countries): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $service   = $container->get(RoutingService::class);

        $service->countries = $countries;

        $this->expectException(\InvalidArgumentException::class);

        $service->findRoute('ITA', 'DEU');
    }

    /**
     * @return string[][]
     */
    public function unaccessibleCountriesDataProvider(): array
    {
        return [
            'no origin' => ['countries' => ['DEU']],
            'no destination' => ['countries' => ['ITA']],
            'no origin borders' => [
                'countries' => [
                    'ITA' => ['borders' => []],
                    'DEU' => ['borders' => ['ITA']],
                ],
            ],
            'no destination borders' => [
                'countries' => [
                    'ITA' => ['borders' => ['DEU']],
                    'DEU' => ['borders' => []],
                ],
            ],
        ];
    }
}
