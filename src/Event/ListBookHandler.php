<?php

namespace App\Event;

use Exception;
use App\Entity\UserBookList;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserBookListRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\DataGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListBookHandler implements EventSubscriberInterface
{
    private $bookRepository;
    private $userBookListRepository;
    private $em;
    private $security;
    private $dataGenerator;

    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository, Security $security, UserBookListRepository $userBookListRepository, DataGenerator $dataGenerator)
    {
        $this->bookRepository = $bookRepository;
        $this->userBookListRepository = $userBookListRepository;
        $this->em = $em;
        $this->security = $security;
        $this->dataGenerator = $dataGenerator;
    }
    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::VIEW => [
                ['bookChecker', EventPriorities::PRE_VALIDATE],
                ['resetListOrder', EventPriorities::POST_WRITE]
            ]
        ];
    }

    /**
     * Before adding a book on the user's list, an api request is being made to take data about the current book being added
     *
     * @param ViewEvent $event
     * 
     * @return void
     */
    public function bookChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserBookList && $event->getRequest()->isMethod('POST')) {

            //Check if request datas are correct
            $book = $datas->getBook();
            if (!$book) {
                $event->setResponse(new JsonResponse(['message' => 'There is a issue on your request. Please refer to the Api\'s documentation'], 422));
                return $event;
            }
            $apiCode = $book->getApiCode();
            if (!$apiCode) {
                $event->setResponse(new JsonResponse(['message' => 'You have to specify a apiCode'], 400));
                return $event;
            }

            $user = $this->security->getUser();

            //If the book is not already on the database, add it  
            $bookAlreadyInDB = $this->bookRepository->findByApiCode($apiCode);
            if (!$bookAlreadyInDB) {
                $book = $this->dataGenerator->generateBook($apiCode);
                if (!$book) {
                    $event->setResponse(new JsonResponse(['message' => 'Book not found. Please check the api code.'], 404));
                    return $event;
                }
                $this->em->persist($book);
            } else {
                $book = $bookAlreadyInDB;
            }
            $datas->setBook($book);

            //check if user has already the movie on his list
            $bookAlreadyInList = $this->userBookListRepository->searchByUserAndBook($user, $book);
            if ($bookAlreadyInList) {
                $event->setResponse(new JsonResponse(['message' => 'Vous avez déja ce livre dans votre liste'], 400));
            }

            //set the list order for the book currently added
            $currentUserBooksInList = $this->userBookListRepository->searchByUser($user, $book);
            $listOrder = count($currentUserBooksInList) + 1;
            $datas->setListOrder($listOrder);

            $datas->setUser($user);
        }
    }

    /**
     * If a book is removed from a list, the list order of the current user are rewritten
     *
     * @param ViewEvent $event
     * 
     * @return void
     */
    public function resetListOrder(ViewEvent $event)
    {
        $datas = $event->getRequest()->attributes->get('data');
        if ($datas instanceof UserBookList && $event->getRequest()->isMethod('DELETE')) {
            $user  = $this->security->getUser();
            $currentUserBooksInList =  $this->userBookListRepository->searchByUser($user);
            $i = 1;
            foreach ($currentUserBooksInList as $book) {
                $book->setListOrder($i);
                $this->em->persist($book);
                $i++;
            }
            $this->em->flush();
        }
    }
}
