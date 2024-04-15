<?php

namespace App\Domain\Spi;

use App\Domain\Book\EntityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.persister')]
interface PersisterInterface
{
    public function save(EntityInterface $entity, ?string $managerName = null): void;

    /**
     * @param iterable<EntityInterface> $entities
     */
    public function saveAll(iterable $entities, ?string $managerName = null): void;

    /**
     * @param iterable<Book> $books
     */
    public function insertOrIgnore(iterable $books): void;
}
