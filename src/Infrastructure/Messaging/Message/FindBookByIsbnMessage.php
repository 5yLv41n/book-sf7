<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Message;

final readonly class FindBookByIsbnMessage
{
    public function __construct(
        public string $isbn,
    ) {
    }
}
