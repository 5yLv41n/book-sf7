<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Message;

use App\Infrastructure\DTO\CreateBookDTO;

final readonly class SaveBookMessage
{
    public function __construct(
        public CreateBookDTO $dto,
    ) {
    }
}
