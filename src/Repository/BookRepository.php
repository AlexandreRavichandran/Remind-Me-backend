<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Search a book in the book database by his api code
     * 
     * @param string $apiCode The api code of the book to search
     * 
     * @return Book|null If the book exists in the database, it is returned
     */
    public function findByApiCode(string $apiCode)
    {
        return $this
            ->createQueryBuilder('b')
            ->andWhere('b.apiCode = :apiCode')
            ->setParameter('apiCode', $apiCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
