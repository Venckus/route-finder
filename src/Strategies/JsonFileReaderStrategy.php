<?php
declare(strict_types=1);

namespace App\Strategies;

use Symfony\Component\Serializer\Encoder\JsonDecode;

class JsonFileReaderStrategy
{
    public function __construct(
        private string $countriesDataPath,
    ) {
    }

    public function readFile(): array
    {
        $jsonDecoder = new JsonDecode([JsonDecode::ASSOCIATIVE => true]);

        return $jsonDecoder->decode(file_get_contents($this->countriesDataPath), 'json');
    }
}