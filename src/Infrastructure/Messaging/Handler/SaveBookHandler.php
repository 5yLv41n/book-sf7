<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Handler;

use App\Domain\Book\Book;
use App\Domain\Book\Exception\EntityNotPersistException;
use App\Infrastructure\Messaging\Message\SaveBookMessage;
use App\Infrastructure\Persister\MultiplePersister;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SaveBookHandler
{
    public function __construct(
        public MultiplePersister $persister,
    ) {
    }

    public function __invoke(SaveBookMessage $message): void
    {
        $book = Book::createFromDto($message->dto);

        try {
            $this->persister->save($book);
        } catch (EntityNotPersistException $exception) {
            // store event
            echo 'STORE EVENT';
        }
    }
}
