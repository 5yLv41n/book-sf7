<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Handler;

use App\Domain\Book\Exception\EntityNotPersistException;
use App\Infrastructure\Messaging\Message\SaveAsyncBookMessage;
use App\Infrastructure\Persister\MultiplePersister;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SaveAsyncBookHandler
{
    public function __construct(
        public MultiplePersister $persister,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(SaveAsyncBookMessage $message): void
    {
        try {
            $this->logger->warning('Save');
            $this->persister->saveAll($message->books);
        } catch (EntityNotPersistException $exception) {
            // store event
            $this->logger->warning('No Save');
        }
    }
}
