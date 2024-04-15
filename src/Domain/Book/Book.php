<?php

namespace App\Domain\Book;

use DateTimeImmutable;
use JsonSerializable;

class Book implements EntityInterface, JsonSerializable
{
    public int $id;

    public DateTimeImmutable $createdAt;
    public DateTimeImmutable $updatedAt;

    public function __construct(
        readonly public string $title,
        readonly public string $isbn10,
        readonly public ?string $isbn13,
        readonly public ?int $page,
        readonly public ?DateTimeImmutable $publicationDate,
    ) {
    }

    public static function createFromDto(BookDtoInterface $dto): self
    {
        return new self(
            title: $dto->title,
            isbn10: $dto->isbn10,
            isbn13: $dto->isbn13,
            page: $dto->page,
            publicationDate: DateTimeImmutable::createFromFormat('Y-m-d', $dto->publicationDate),
        );
    }

    public function setCreatedAt()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function setUpdatedAt()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'title' => $this->title,
            'isbn10' => $this->isbn10,
            'isbn13' => $this->isbn13,
            'page' => $this->page,
            'publicationDate' => $this->publicationDate->format('d/m/Y'),
        ];
    }
}
