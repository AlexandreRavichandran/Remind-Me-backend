<?php

namespace App\Event;

use App\Entity\UserMovieList;
use App\Entity\UserMusicList;
use App\Repository\MusicRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class ListMusicHandler implements EventSubscriberInterface
{
    private $musicRepository;
    private $em;
    private $security;

    public function __construct(MusicRepository $musicRepository, EntityManagerInterface $em, Security $security)
    {
        $this->musicRepository = $musicRepository;
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

            //If the music is not already on the database, add it  
            $music = $datas->getMusic();


            $exists = $this->musicRepository->findOneBy(['name' => $music->getName(), 'type' => $music->getType(), 'artist' => $music->getArtist()], null);
            if (!$exists) {
                $this->em->persist($music);
            } else {
                $datas->setMusic($exists);
            }


            $datas->setUser($this->security->getUser());
        }
    }
}
