<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidCountryException extends HttpException
{
    public function __construct(
        string $message = 'Invalid country.',
        int $statusCode = 422,
        array $headers = [],
        \Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}