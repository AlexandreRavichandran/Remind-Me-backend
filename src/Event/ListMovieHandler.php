<?php

namespace App\Event;

use App\Entity\UserMovieList;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\MovieRepository;
use App\Repository\UserMovieListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

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
            KernelEvents::VIEW => ['movieChecker', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function movieChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserMovieList && $event->getRequest()->isMethod('POST')) {

            $movie = $datas->getMovie();
            $user = $this->security->getUser();

            //If the book is not already on the database, add it  
            $movies = $this->movieRepository->findAll();
            $exists = in_array($movie, $movies);
            if (!$exists) {
                $this->em->persist($movie);
            } else {
                $datas->setMovie($movie);
            }

            //set the list order for the book currently added
            $currentUserMoviesInList = $this->userMovieListRepository->searchByUser($user, $movie);
            $listOrder = count($currentUserMoviesInList) + 1;
            $datas->setListOrder($listOrder);

            $datas->setUser($user);
        }
    }
}
