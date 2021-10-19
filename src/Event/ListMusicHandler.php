<?php

namespace App\Event;

use App\Entity\UserMusicList;
use App\Repository\MusicRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserMusicListRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListMusicHandler implements EventSubscriberInterface
{
    private $musicRepository;
    private $userMusicListRepository;
    private $em;
    private $security;

    public function __construct(MusicRepository $musicRepository, UserMusicListRepository $userMusicListRepository, EntityManagerInterface $em, Security $security)
    {
        $this->musicRepository = $musicRepository;
        $this->userMusicListRepository = $userMusicListRepository;
        $this->em = $em;
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['musicChecker', EventPriorities::PRE_VALIDATE],
                ['resetListOrder', EventPriorities::POST_WRITE]
            ]
        ];
    }

    public function MusicChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserMusicList && $event->getRequest()->isMethod('POST')) {

            $music = $datas->getMusic();
            $user = $this->security->getUser();

            //If the music is not already on the database, add it  
            $musicAlreadyInDB = $this->musicRepository->findByApiCode($music->getApiCode());
            if (!$musicAlreadyInDB) {
                $this->em->persist($music);
            } else {
                $datas->setMusic($musicAlreadyInDB);
            }

            //check if user has already the music on his list
            $musicAlreadyInList = $this->userMusicListRepository->searchByUserAndMusic($user, $music);
            if ($musicAlreadyInList) {
                $event->setResponse(new JsonResponse(['message' => 'Vous avez déja cette musique dans votre liste'], 400));
            }

            //set the list order for the music currently added
            $currentUserMusicsInList = $this->userMusicListRepository->searchByUser($user, $music);
            $listOrder = count($currentUserMusicsInList) + 1;
            $datas->setListOrder($listOrder);

            $datas->setUser($user);
        }
    }

    public function resetListOrder(ViewEvent $event)
    {
        $datas = $event->getRequest()->attributes->get('data');
        if ($datas instanceof UserMusicList && $event->getRequest()->isMethod('DELETE')) {
            $user  = $this->security->getUser();
            $currentUserMusicsInList =  $this->userMusicListRepository->searchByUser($user);
            $i = 1;
            foreach ($currentUserMusicsInList as $music) {
                $music->setListOrder($i);
                $this->em->persist($music);
                $i++;
            }
            $this->em->flush();
        }
    }
}
