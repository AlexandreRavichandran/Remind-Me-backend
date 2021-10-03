<?php

namespace App\Repository;

use App\Entity\UserMovieList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMovieList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMovieList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMovieList[]    findAll()
 * @method UserMovieList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMovieListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMovieList::class);
    }

    // /**
    //  * @return UserMovieList[] Returns an array of UserMovieList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMovieList
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
