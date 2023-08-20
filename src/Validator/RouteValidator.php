<?php

namespace App\Validator;

use App\Exception\InvalidCountryException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RouteValidator
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    {
    }

    public function validateCountries(mixed $origin, mixed $destination): void
    {
        $violations = $this->validator->validate(strtoupper($origin), new Country(alpha3: true));
        if ($violations->count() > 0) {
            throw new InvalidCountryException(message: 'Origin value is not a valid country.');
        }

        $violations = $this->validator->validate(strtoupper($destination), new Country(alpha3: true));
        if ($violations->count() > 0) {
            throw new InvalidCountryException(message: 'Destination value is not a valid country.');
        }
    }
}
