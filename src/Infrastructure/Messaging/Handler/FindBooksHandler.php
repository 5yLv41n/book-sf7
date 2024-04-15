<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Handler;

use App\Domain\Book\Book;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Infrastructure\Messaging\Message\FindBooksMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class FindBooksHandler
{
    public function __construct(
        private BookRepositoryInterface $mariaDbBookRepository,
        private BookRepositoryInterface $postgresBookRepository,
    ) {
    }

    /**
     * @return iterable<Book>
     */
    public function __invoke(FindBooksMessage $message): iterable
    {
        $book = $this->mariaDbBookRepository->findBooks();

        if (true === empty($book)) {
            $book = $this->postgresBookRepository->findBooks();
        }

        return $book;
    }
}
