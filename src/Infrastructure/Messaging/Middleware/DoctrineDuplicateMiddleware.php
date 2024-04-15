<?php

namespace App\Infrastructure\Messaging\Middleware;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

final class DoctrineDuplicateMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (UniqueConstraintViolationException|Throwable $exception) {
            throw new UnrecoverableMessageHandlingException($exception->getMessage());
        }
    }
}
