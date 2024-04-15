<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Handler;

use App\Domain\Book\Book;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Domain\Book\ServiceGateway\ServiceGatewayInterface;
use App\Infrastructure\Messaging\Message\FindBookByIsbnMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class FindBookByIsbnHandler
{
    public function __construct(
        private BookRepositoryInterface $mariaDbBookRepository,
        private BookRepositoryInterface $postgresBookRepository,
        private ServiceGatewayInterface $serviceGateway,
    ) {
    }

    public function __invoke(FindBookByIsbnMessage $message): ?Book
    {
        $book = $this->mariaDbBookRepository->findBookByIsbn($message->isbn);

        if (null === $book) {
            $book = $this->postgresBookRepository->findBookByIsbn($message->isbn);

            if (null === $book) {
                $book = $this->serviceGateway->getBook($message->isbn);

                if (null !== $book) {
                    // save event
                }
            }
        }

        return $book;
    }
}
