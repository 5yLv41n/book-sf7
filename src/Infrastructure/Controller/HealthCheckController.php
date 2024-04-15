<?php

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HealthCheckController
{
    #[Route(path: '/health', name: 'health_check', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response(status: Response::HTTP_OK);
    }
}
