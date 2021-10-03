<?php

namespace App\Repository;

use App\Entity\UserMusicList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMusicList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMusicList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMusicList[]    findAll()
 * @method UserMusicList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMusicListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMusicList::class);
    }

    // /**
    //  * @return UserMusicList[] Returns an array of UserMusicList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMusicList
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
