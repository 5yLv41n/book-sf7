<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Message;

use App\Domain\Book\Book;

final readonly class SaveAsyncBookMessage
{
    /**
     * @param iterable<Book> $books
     */
    public function __construct(
        public iterable $books,
    ) {
    }
}
