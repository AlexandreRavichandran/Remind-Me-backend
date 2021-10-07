<?php

namespace App\DoctrineExtension;

use App\Entity\UserBookList;
use App\Entity\UserMovieList;
use App\Entity\UserMusicList;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function customizeQuery(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $user = $this->security->getUser();


        if ($resourceClass === UserMovieList::class || $resourceClass === UserMusicList::class || $resourceClass === UserBookList::class) {
            $alias = $queryBuilder->getRootAliases()[0];



            $queryBuilder->andWhere($alias . '.user=:user')
                ->setParameter('user', $user);
        }
    }
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $this->customizeQuery($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        $this->customizeQuery($queryBuilder, $resourceClass);
    }
}
