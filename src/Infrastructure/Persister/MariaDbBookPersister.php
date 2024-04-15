<?php

declare(strict_types=1);

namespace App\Infrastructure\Persister;

use App\Domain\Book\Book;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('mariadb')]
final class MariaDbBookPersister extends AbstractPersister
{
    protected const DEFAULT_ENTITY_MANAGER = 'mariadb';

    /**
     * @param iterable<Book> $books
     */
    public function insertOrIgnore(iterable $books): void
    {
        $connection = $this->getEntityManager(self::DEFAULT_ENTITY_MANAGER)->getConnection();
        $chunks = iterable_chunk($books, 10);
        foreach ($chunks as $chunk) {
            $values = [];
            $params = [];
            foreach ($chunk as $book) {
                $values[] = '(?,?,?,?,?)';
                $params[] = $book->isbn10;
                $params[] = $book->isbn13;
                $params[] = $book->title;
                $params[] = $book->page;
                $params[] = $book->publicationDate?->format('Ymd');
            }

            $values = implode(',', $values);

            $query = <<<SQL
                INSERT IGNORE INTO book (isbn10, isbn13, title, page, publicationDate) VALUES {$values};
                SQL;

            $connection->executeQuery($query, $params);
        }
    }

    protected function getEntityClassName(): string
    {
        return Book::class;
    }
}
