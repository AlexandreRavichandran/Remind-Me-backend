<?php

namespace App\Event;

use App\Entity\UserMovieList;
use App\Entity\UserMusicList;
use App\Repository\MusicRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserMusicListRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
            KernelEvents::VIEW => ["MusicChecker", EventPriorities::PRE_VALIDATE]
        ];
    }

    public function MusicChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserMusicList && $event->getRequest()->isMethod('POST')) {

            $music = $datas->getMusic();
            $user = $this->security->getUser();
            
            //If the music is not already on the database, add it  
            $musics = $this->musicRepository->findAll();
            $exists = in_array($music, $musics);
            if (!$exists) {
                $this->em->persist($music);
            } else {
                $datas->setMusic($music);
            }
            
            //set the list order for the music currently added
            $currentUserMusicsInList = $this->userMusicListRepository->searchByUser($user, $music);
            $listOrder = count($currentUserMusicsInList) + 1;
            $datas->setListOrder($listOrder);
            
            $datas->setUser($user);
            $datas->setUser($this->security->getUser());
        }
    }
}
