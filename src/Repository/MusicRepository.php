<?php

namespace App\Repository;

use App\Entity\Music;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Music|null find($id, $lockMode = null, $lockVersion = null)
 * @method Music|null findOneBy(array $criteria, array $orderBy = null)
 * @method Music[]    findAll()
 * @method Music[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Music::class);
    }


    /**
     * Search a music in the music database by his api code
     * 
     * @param string $apiCode The api code of the music to search
     * 
     * @return Music|null If the music exists in the database, it is returned.
     */
    public function findByApiCode(string $apiCode)
    {
        return $this
            ->createQueryBuilder('m')
            ->andWhere('m.apiCode = :apiCode')
            ->setParameter('apiCode', $apiCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
