<?php

namespace App\Domain\Book\Repository;

use App\Domain\Book\Book;

interface BookRepositoryInterface
{
    public function findBookByIsbn(string $isbn): ?Book;

    /**
     * @return iterable<Book>
     */
    public function findBooks(): iterable;
}
