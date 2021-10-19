<?php

namespace App\Event;

use App\Entity\UserMovieList;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserMovieListRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListMovieHandler implements EventSubscriberInterface
{
    private $movieRepository;
    private $userMovieListRepository;
    private $em;
    private $security;

    public function __construct(MovieRepository $movieRepository, UserMovieListRepository $userMovieListRepository, EntityManagerInterface $em, Security $security)
    {
        $this->movieRepository = $movieRepository;
        $this->userMovieListRepository = $userMovieListRepository;
        $this->em = $em;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['movieChecker', EventPriorities::PRE_VALIDATE],
                ['resetListOrder', EventPriorities::POST_WRITE]
            ]
        ];
    } 

    public function movieChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserMovieList && $event->getRequest()->isMethod('POST')) {

            $movie = $datas->getMovie();
            $user = $this->security->getUser();

            //If the movie is not already on the database, add it  
            $bookAlreadyInDB = $this->movieRepository->findByApiCode($movie->getApiCode());
            if (!$bookAlreadyInDB) {
                $this->em->persist($movie);
            } else {
                $datas->setMovie($bookAlreadyInDB);
            }

            //check if user has already the movie on his list
            $movieAlreadyInList = $this->userMovieListRepository->searchByUserAndMovie($user, $movie);
            if ($movieAlreadyInList) {
                $event->setResponse(new JsonResponse(['message' => 'Vous avez déja ce film dans votre liste'], 400));
            }

            //set the list order for the movie currently added
            $currentUserMoviesInList = $this->userMovieListRepository->searchByUser($user, $movie);
            $listOrder = count($currentUserMoviesInList) + 1;
            $datas->setListOrder($listOrder);

            $datas->setUser($user);
        }
    }

    public function resetListOrder(ViewEvent $event)
    {
        $datas = $event->getRequest()->attributes->get('data');
        if ($datas instanceof UserMovieList && $event->getRequest()->isMethod('DELETE')) {
            $user  = $this->security->getUser();
            $currentUserMoviesInList =  $this->userMovieListRepository->searchByUser($user);
            $i = 1;
            foreach ($currentUserMoviesInList as $movie) {
                $movie->setListOrder($i);
                $this->em->persist($movie);
                $i++;
            }
            $this->em->flush();
        }
    } 
}
