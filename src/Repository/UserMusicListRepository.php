<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Music;
use App\Entity\UserMusicList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function searchByUserAndMusic(User $user, Music $music)
    {
        return $this
            ->createQueryBuilder('uml')
            ->join('uml.music', 'm')
            ->join('uml.user', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('m.apiCode = :apiCode')
            ->setParameters([
                ':userId' => $user->getId(),
                ':apiCode' => $music->getApiCode()
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
