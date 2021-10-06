<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserMovieList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function searchByUser(User $user): array
    {
        return $this
                ->createQueryBuilder('uml')
                ->join('uml.user','u')
                ->andWhere('u.id = :userId')
                ->setParameter('userId',$user->getId())
                ->getQuery()
                ->getResult();
    }
}
