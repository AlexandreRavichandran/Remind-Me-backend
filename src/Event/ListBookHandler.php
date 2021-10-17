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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListBookHandler implements EventSubscriberInterface
{
    private $bookRepository;
    private $userBookListRepository;
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository, Security $security, UserBookListRepository $userBookListRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->userBookListRepository = $userBookListRepository;
        $this->em = $em;
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::VIEW => ['bookChecker', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function bookChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserBookList && $event->getRequest()->isMethod('POST')) {

            $book = $datas->getBook();
            $user = $this->security->getUser();

            //If the book is not already on the database, add it  
            $bookAlreadyInDB = $this->bookRepository->findByApiCode($book->getApiCode());
            if (!$bookAlreadyInDB) {
                $this->em->persist($book);
            } else {
                $datas->setBook($bookAlreadyInDB);
            }

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
}
