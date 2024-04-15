<?php

namespace App\Infrastructure\Persister;

use App\Domain\Book\EntityInterface;
use App\Domain\Book\Exception\EntityNotPersistException;
use App\Domain\Spi\PersisterInterface;
use Doctrine\DBAL\Exception\ServerException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractPersister implements PersisterInterface
{
    protected const DEFAULT_ENTITY_MANAGER = 'mariadb';

    public function __construct(
        protected ManagerRegistry $managerRegistry,
        protected ClockInterface $clock,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws EntityNotPersistException
     */
    public function save(EntityInterface $entity, ?string $managerName = null): void
    {
        $this->getEntityManager($managerName)->persist($entity);
        try {
            $this->flush($managerName);
        } catch (ORMException|ORMInvalidArgumentException|ServerException $exception) {
            $this->logger->warning('An error occurred during persist', [
                'exception' => $exception,
            ]);

            throw new EntityNotPersistException(previous: $exception);
        }
    }

    /**
     * @param iterable<EntityInterface> $entities
     */
    public function saveAll(iterable $entities, ?string $managerName = null): void
    {
        $entitiesChunk = iterable_chunk($entities, 50);
        foreach ($entitiesChunk as $entityChunk) {
            foreach ($entityChunk as $entity) {
                $this->getEntityManager($managerName)->persist($entity);
            }

            try {
                $this->flush($managerName);
            } catch (ORMException $exception) {
                $this->logger->warning('An error occurred during persist', [
                    'exception' => $exception,
                ]);
            }
        }
    }

    abstract protected function getEntityClassName(): string;

    protected function flush(?string $managerName = null): void
    {
        $this->getEntityManager($managerName)->flush();
    }

    protected function getEntityManager(?string $managerName = null): EntityManager
    {
        return $this->managerRegistry->getManager($managerName ?? static::DEFAULT_ENTITY_MANAGER);
    }

    protected function getRepository(?string $entityName = null, ?string $managerName = null): EntityRepository
    {
        return $this->getEntityManager($managerName)->getRepository($entityName ?? $this->getEntityClassName());
    }
}
