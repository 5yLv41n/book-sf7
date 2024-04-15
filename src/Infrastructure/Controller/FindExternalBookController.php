<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class FindExternalBookController
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $queryBus,
        private RateLimiterFactory $getBookApiLimiter,
    ) {
        $this->messageBus = $queryBus;
    }

    #[Route(
        path: '/external/books/{publicationDate}',
        name: 'external_last_books',
        methods: ['GET'],
        requirements: ['publicationDate' => Requirement::DATE_YMD]),
    ]
    public function __invoke(
        string $publicationDate,
        Request $request,
    ): JsonResponse|Response {
        $limiter = $this->getBookApiLimiter->create($request->getClientIp());
        $limit = $limiter->consume();
        $headers = [
            'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
            'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp() - time(),
            'X-RateLimit-Limit' => $limit->getLimit(),
        ];

        if (false === $limit->isAccepted()) {
            return new Response(status: Response::HTTP_TOO_MANY_REQUESTS, headers: $headers);
        }

        return new JsonResponse(data: $publicationDate, status: JsonResponse::HTTP_OK);
    }
}
