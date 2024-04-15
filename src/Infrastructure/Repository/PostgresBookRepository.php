<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Book\Book;
use App\Domain\Book\Repository\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PostgresBookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @todo
     */
    public function findBookByIsbn(string $isbn): ?Book
    {
        return null;
    }

    /**
     * @return iterable<Book>
     */
    public function findBooks(): iterable
    {
        $queryBuilder = $this->createQueryBuilder('b');
        $queryBuilder->where('b.id > :lastId')->setMaxResults(20);
        do {
            $queryBuilder->setParameter(':lastId', $lastId ?? 0);
            $results = $queryBuilder->getQuery()->getResult();
            foreach($results as $result) {
                $lastId = $result['id'];
            
                yield $result;
            }
        } while(false === empty($results));
    }
}
