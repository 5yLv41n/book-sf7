<?php

declare(strict_types=1);

namespace App\Infrastructure\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class FindBookDTO
{
    public function __construct(
        #[Assert\Isbn(
            type: Assert\Isbn::ISBN_10,
        )]
        public string $isbn10,
    ) {
    }
}
