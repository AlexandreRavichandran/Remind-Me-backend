<?php

namespace App\Event;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserBookList;
use App\Repository\BookRepository;
use App\Repository\UserBookListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

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
            $books = $this->bookRepository->findAll();
            $exists = in_array($book, $books);
            if (!$exists) {
                $this->em->persist($book);
            } else {
                $datas->setBook($book);
            }

            //set the list order for the book currently added
            $currentUserBooksInList = $this->userBookListRepository->searchByUser($user, $book);
            $listOrder = count($currentUserBooksInList) + 1;
            $datas->setListOrder($listOrder);

            $datas->setUser($user);
        }
    }
}
