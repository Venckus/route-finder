<?php
declare(strict_types=1);

namespace App\Services;

class RoutingService
{
    private const KEY_BORDERS = 'borders';

    /**
     * @var string[][][]|bool[][][]
     */
    public array $countries;

    public function __construct(
        private string $origin,
        private string $destination
    ) {
    }

    /**
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function findRoute(): array
    {
        if (!$this->isCountriesAccessible($this->origin, $this->destination)) {
            throw new \InvalidArgumentException();
        }

        if ($this->origin === $this->destination) {
            return [$this->origin];
        }

        if (in_array($this->destination, $this->countries[$this->origin][self::KEY_BORDERS])) {
            return [$this->origin, $this->destination];
        }

        $this->loadCoutriesData();

        return $this->findBreadthFirstPath();
    }

    /**
     * @return string[]|null
     */
    public function findBreadthFirstPath(): ?array
    {
        $queue = new \SplQueue();
        $queue->enqueue([$this->origin]);
        $visited = [$this->origin];

        while (!$queue->isEmpty()) {
            $path = $queue->dequeue();
            $lastVisited = $path[count($path) - 1];

            if ($lastVisited === $this->destination) {
                return $path;
            }

            foreach ($this->countries[$lastVisited][self::KEY_BORDERS] as $country) {
                if (!in_array($country, $visited)) {
                    $visited[] = $country;
                    $newPath   = $path;
                    $newPath[] = $country;

                    $queue->enqueue($newPath);
                }
            }
        }

        return null;
    }

    private function isCountriesAccessible(): bool
    {
        return isset($this->countries[$this->origin], $this->countries[$this->destination])
            && $this->countries[$this->origin][self::KEY_BORDERS] !== []
            && $this->countries[$this->destination][self::KEY_BORDERS] !== []
        ;
    }

    public function loadCoutriesData(): void
    {
        // implement lead data strategy pattern
        $this->countries = json_decode(file_get_contents(__DIR__ . '/../data/countries.json'), true);
    }
}