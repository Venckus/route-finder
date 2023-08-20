<?php
declare(strict_types=1);

namespace App\Services;

use App\Strategies\JsonFileReaderStrategy;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RoutingService
{
    private const KEY_BORDERS = 'borders';
    private const DATA_DIR    = 'data';
    private const DATA_FILE_TYPE = 'json';

    private string $origin;
    private string $destination;
    private string $countriesDataFilePath;

    /**
     * @var string[][][]|bool[][][]
     */
    public array $countries;

    public function __construct(
        #[Autowire(env: 'COUNTRIES_DATA_FILE_NAME')]
        private string $countriesDataFileName,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
    ) {
    }

    public function setCountriesDataFilePath(): self
    {
        $this->countriesDataFilePath = implode(DIRECTORY_SEPARATOR, [
            $this->projectDir,
            self::DATA_DIR,
            $this->countriesDataFileName
        ]);

        return $this;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = strtoupper($origin);
        return $this;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = strtoupper($destination);
        return $this;
    }

    /**
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function findRoute(string $origin, string $destination): array
    {
        $this->setOrigin($origin)->setDestination($destination);

        if (!isset($this->countries)) {
            $this->loadCoutriesData();
        }

        if (!$this->isCountriesAccessible($this->origin, $this->destination)) {
            throw new \InvalidArgumentException('Countries are not accessible', 400);
        }

        if ($this->origin === $this->destination) {
            return [$this->origin];
        }

        if (in_array($this->destination, $this->countries[$this->origin][self::KEY_BORDERS])) {
            return [$this->origin, $this->destination];
        }

        $route = $this->findBreadthFirstPath();
        if ($route === null) {
            throw new \InvalidArgumentException('There is no land crossing', 400);
        }

        return $route;
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

    public function loadCoutriesData(): self
    {
        $this->setCountriesDataFilePath();

        $strategy = match (explode('.', $this->countriesDataFileName)[1]) {
            self::DATA_FILE_TYPE => new JsonFileReaderStrategy($this->countriesDataFilePath),
            default => throw new \InvalidArgumentException('Invalid data source'),
        };

        $this->countries = $strategy->readFile();

        return $this;
    }
}