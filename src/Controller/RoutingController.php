<?php

namespace App\Controller;

use App\Exception\InvalidCountryException;
use App\Services\RoutingService;
use App\Validator\RouteValidator;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use UnprocessableEntityHttpException;

class RoutingController extends AbstractController
{
    public function __construct(
        private RouteValidator $routeValidator,
        private RoutingService $routingService,
    ) {
    }

    #[Route('/routing/{origin}/{destination}', name: 'routing_show', methods: ['GET'])]
    public function __invoke(string $origin, string $destination): JsonResponse
    {
        try {
            $this->routeValidator->validateCountries($origin, $destination);

            $route = $this->routingService->findRoute($origin, $destination);
    
            return $this->json(['route' => $route]);
        } catch (InvalidCountryException $e) {
            return $this->json(['error' => $e->getMessage()], 422);
        }catch (InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
