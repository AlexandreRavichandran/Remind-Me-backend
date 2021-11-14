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
use App\Service\DataGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListMovieHandler implements EventSubscriberInterface
{
    private $movieRepository;
    private $userMovieListRepository;
    private $em;
    private $security;
    private $dataGenerator;

    public function __construct(MovieRepository $movieRepository, UserMovieListRepository $userMovieListRepository, EntityManagerInterface $em, Security $security, DataGenerator $dataGenerator)
    {
        $this->movieRepository = $movieRepository;
        $this->userMovieListRepository = $userMovieListRepository;
        $this->em = $em;
        $this->security = $security;
        $this->dataGenerator = $dataGenerator;
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

    /**
     * Before adding a movie on the user's list, an api request is being made to take data about the current movie being added
     *
     * @param ViewEvent $event
     * 
     * @return void
     */
    public function movieChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserMovieList && $event->getRequest()->isMethod('POST')) {

            //Check if request datas are correct
            $movie = $datas->getMovie();
            if (!$movie) {
                $event->setResponse(new JsonResponse(['message' => 'There is a issue on your request. Please refer to the Api\'s documentation'], 422));
                return $event;
            }
            $user = $this->security->getUser();
            $apiCode = $movie->getApiCode();
            if (!$apiCode) {
                $event->setResponse(new JsonResponse(['message' => 'You have to specify the movie\'s api code', 400]));
                return $event;
            }

            //If the movie is not already on the database, add it  
            $movieAlreadyInDB = $this->movieRepository->findByApiCode($apiCode);
            if (!$movieAlreadyInDB) {
                $movie = $this->dataGenerator->generateMovie($apiCode);
                if (!$movie) {
                    $event->setResponse(new JsonResponse(['message' => 'Movie not found. Please verify the api code.'], 404));
                    return $event;
                }
                $this->em->persist($movie);
            } else {
                $movie = $movieAlreadyInDB;
            }
            $datas->setMovie($movie);

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

    /**
     * If a movie is removed from a list, the list order of the current user are rewritten
     *
     * @param ViewEvent $event
     * 
     * @return void
     */
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
