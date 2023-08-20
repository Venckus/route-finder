<?php

namespace App\Controller;

use App\Services\RoutingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoutingController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private RoutingService $routingService,
    ) {
    }

    #[Route('/routing/{origin}/{destination}', name: 'routing_show', methods: ['GET'])]
    public function __invoke(string $origin, string $destination): JsonResponse
    {
        try {
            $violations = $this->validator->validate(strtoupper($origin), new Country(alpha3: true));
            if ($violations->count() > 0) {
                return $this->json($violations->get(0)->getMessage(), 422);
            }

            $violations = $this->validator->validate(strtoupper($destination), new Country(alpha3: true));
            if ($violations->count() > 0) {
                return $this->json($violations->get(0)->getMessage(), 422);
            }

            $route = $this->routingService->findRoute($origin, $destination);
    
            return $this->json(['route' => $route]);
        } catch (\InvalidArgumentException $e) {
            return $this->json($e->getMessage(), 400);
        }
    }
}
