<?php

namespace App\Infrastructure\Persister;

use App\Domain\Book\EntityInterface;
use App\Domain\Book\Exception\EntityNotPersistException;
use App\Domain\Book\Exception\PersisterNotFoundException;
use App\Domain\Spi\PersisterInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

final class MultiplePersister
{
    public function __construct(
        #[TaggedLocator(tag: 'app.persister')]
        private ContainerInterface $persisters,
        private LoggerInterface $logger,
        private array $dbs,
    ) {
    }

    /**
     * @throws EntityNotPersistException
     * @throws PersisterNotFoundException
     */
    public function save(EntityInterface $entity): void
    {
        $errorCount = 0;
        foreach ($this->dbs as $db) {
            $persister = $this->getPersister($db);
            try {
                $persister->save(entity: $entity, managerName: $db);
            } catch (EntityNotPersistException $exception) {
                ++$errorCount;

                if ($errorCount === count($this->dbs)) {
                    throw $exception;
                }
            }
        }
    }

    /**
     * @param iterable<EntityInterface> $entities
     */
    public function saveAll(iterable $entities): void
    {
        foreach ($this->dbs as $db) {
            $persister = $this->getPersister($db);
            $persister->insertOrIgnore($entities);
        }
    }

    /**
     * @throws PersisterNotFoundException
     */
    private function getPersister(string $db): PersisterInterface
    {
        try {
            return $this->persisters->get($db);
        } catch (ServiceNotFoundException $exception) {
            throw new PersisterNotFoundException(previous: $exception);
        }
    }
}
