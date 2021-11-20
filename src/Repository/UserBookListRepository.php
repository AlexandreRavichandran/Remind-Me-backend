<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\UserBookList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method UserBookList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBookList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBookList[]    findAll()
 * @method UserBookList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBookListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBookList::class);
    }

    /**
     * Get the book list of an user
     *
     * @param User $user the current user
     * 
     * @return array
     */
    public function searchByUser(User $user): array
    {
        return $this
            ->createQueryBuilder('ubl')
            ->join('ubl.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * Get the book list element by the user and the book
     *
     * @param User $user the current user
     * @param Book $book the book to search on the user's list
     * 
     * @return UserBookList|null the user book list element
     */
    public function searchByUserAndBook(User $user, Book $book)
    {
        return $this
            ->createQueryBuilder('ubl')
            ->join('ubl.book', 'b')
            ->join('ubl.user', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('b.apiCode = :apiCode')
            ->setParameters([
                ':userId' => $user->getId(),
                ':apiCode' => $book->getApiCode()
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
