<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Message;

final readonly class FindExternalBookMessage
{
    public function __construct(
        public string $keyword,
    ) {
    }
}
