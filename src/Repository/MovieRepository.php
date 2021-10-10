<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * Search a movie in the movie database by his api code
     * 
     * @param string $apiCode The api code of the movie to search
     * 
     * @return Movie|null If the movie exists in the database, it is returned
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
