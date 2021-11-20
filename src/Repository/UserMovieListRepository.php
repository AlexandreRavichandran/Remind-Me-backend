<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Movie;
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

    /**
     * Get the movie list of an user
     *
     * @param User $user the current user
     * 
     * @return array
     */
    public function searchByUser(User $user): array
    {
        return $this
            ->createQueryBuilder('uml')
            ->join('uml.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * Get the movie list element by the user and the movie
     *
     * @param User $user the current user
     * @param Movie $movie the book to search on the user's list
     * 
     * @return UserMovieList|null the user movie list element
     */
    public function searchByUserAndMovie(User $user, Movie $movie)
    {
        return $this
            ->createQueryBuilder('uml')
            ->join('uml.movie', 'm')
            ->join('uml.user', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('m.apiCode = :apiCode')
            ->setParameters([
                ':userId' => $user->getId(),
                ':apiCode' => $movie->getApiCode()
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
