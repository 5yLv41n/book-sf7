<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Messaging\Message\FindBooksMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

final class FindBooksController
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $queryBus,
        private RateLimiterFactory $getBookApiLimiter,
        private LoggerInterface $readLogger,
    ) {
        $this->messageBus = $queryBus;
    }

    #[Route(path: '/books', name: 'book_find_books', methods: ['GET'])]
    public function __invoke(
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

        $books = $this->handle(new FindBooksMessage());

        $this->readLogger->info('Read books', [
            'headers' => $request->headers->all(),
        ]);

        return new JsonResponse(data: $books, status: JsonResponse::HTTP_OK);
    }
}
