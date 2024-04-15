<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Book\Book;
use App\Domain\Book\Repository\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MariaDbBookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findBookByIsbn(string $isbn): ?Book
    {
        return $this->findOneBy(criteria: ['isbn10' => $isbn]);
    }

    /**
     * @return iterable<Book>
     */
    public function findBooks(): iterable
    {
        $queryBuilder = $this->createQueryBuilder('b');
        $queryBuilder->where('b.id > :lastId')->setMaxResults(20)->orderBy('b.id');
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
