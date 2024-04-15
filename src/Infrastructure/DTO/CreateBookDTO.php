<?php

declare(strict_types=1);

namespace App\Infrastructure\DTO;

use App\Domain\Book\BookDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateBookDTO implements BookDtoInterface
{
    public function __construct(
        #[Assert\Isbn(
            type: Assert\Isbn::ISBN_10,
        )]
        public ?string $isbn10,
        #[Assert\Isbn(
            type: Assert\Isbn::ISBN_13,
        )]
        public ?string $isbn13,
        #[Assert\NotBlank]
        public ?string $title,
        #[Assert\NotBlank]
        public ?int $page,
        #[Assert\Date(
            message: 'This value {{ value }} is not a valid date.',
        )]
        public ?string $publicationDate,
    ) {
    }
}
