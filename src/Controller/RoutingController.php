<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoutingController extends AbstractController
{
    #[Route('/routing/{origin}/{destination}', name: 'routing_show', methods: ['GET'])]
    public function __invoke(string $origin, string $destination): JsonResponse
    {
        $errors = $request->validate();

        $route = [];
        return $this->json(['route' => $route]);
        // $constraints = new Assert\Collection([
        //     'origin' => [
        //         new Assert\NotBlank(),
        //         new Assert\Type('string'),
        //     ],
        //     'destination' => [
        //         new Assert\NotBlank(),
        //         new Assert\Type('string'),
        //     ],
        // ]);
    
        // $data = [
        //     'origin' => $origin,
        //     'destination' => $destination,
        // ];
    
        // $violations = $this->validator->validate($data, $constraints);
    
        // if (count($violations) > 0) {
        //     $errors = [];
        //     foreach ($violations as $violation) {
        //         $errors[$violation->getPropertyPath()] = $violation->getMessage();
        //     }
    
        //     return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        // }
    
        // // Your logic here to handle the valid $origin and $destination parameters
    
        // return $this->json([
        //     'origin' => $origin,
        //     'destination' => $destination,
        // ]);
    }
}
