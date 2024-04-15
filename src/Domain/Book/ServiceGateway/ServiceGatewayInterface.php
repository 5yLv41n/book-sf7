<?php

namespace App\Domain\Book\ServiceGateway;

use App\Domain\Book\Book;

interface ServiceGatewayInterface
{
    public function getBook(string $isbn): ?Book;

    /**
     * @return iterable<Book>
     */
    public function getBooks(string $keyword): iterable;
}
