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
use App\Service\DataGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListMusicHandler implements EventSubscriberInterface
{
    private $musicRepository;
    private $userMusicListRepository;
    private $em;
    private $security;
    private $dataGenerator;

    public function __construct(MusicRepository $musicRepository, UserMusicListRepository $userMusicListRepository, EntityManagerInterface $em, Security $security, DataGenerator $dataGenerator)
    {
        $this->musicRepository = $musicRepository;
        $this->userMusicListRepository = $userMusicListRepository;
        $this->em = $em;
        $this->security = $security;
        $this->dataGenerator = $dataGenerator;
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

    /**
     * Before adding a music on the user's list, an api request is being made to take data about the current music being added
     *
     * @param ViewEvent $event
     * 
     * @return void
     */
    public function MusicChecker(ViewEvent $event)
    {
        $datas = $event->getControllerResult();
        if ($datas instanceof UserMusicList && $event->getRequest()->isMethod('POST')) {

            //Check if request datas are correct
            $music = $datas->getMusic();
            if (!$music) {
                $event->setResponse(new JsonResponse(['message' => 'There is a issue on your request. Please refer to the Api\'s documentation'], 422));
                return $event;
            }
            $apiCode = $music->getApiCode();
            if (!$apiCode) {
                $event->setResponse(new JsonResponse(['message' => 'You have to specify a apiCode'], 400));
                return $event;
            }
            $type = $datas->getMusic()->getType();
            if (!$type) {
                $event->setResponse(new JsonResponse(
                    ['message' => 'you have to clarify the type of the document (Album or Song)'],
                    400
                ));
                return $event;
            }
            $user = $this->security->getUser();

            //If the music is not already on the database, add it  
            $musicAlreadyInDB = $this->musicRepository->findByApiCode($apiCode);
            if (!$musicAlreadyInDB) {
                $music = $this->dataGenerator->generateMusic($type, $apiCode);
                if (!$music) {
                    $event->setResponse(new JsonResponse(['message' => 'Music not found. Please verify the api code.'], 404));
                    return $event;
                }
                $this->em->persist($music);
            } else {
                $music = $musicAlreadyInDB;
            }

            $datas->setMusic($music);

            //check if user has already the music on his list
            $musicAlreadyInList = $this->userMusicListRepository->searchByUserAndMusic($user, $music);
            if ($musicAlreadyInList) {
                $event->setResponse(new JsonResponse(['message' => 'You have already this music in your list'], 400));
                return $event;
            }

            //set the list order for the music currently added
            $currentUserMusicsInList = $this->userMusicListRepository->searchByUser($user, $music);
            $listOrder = count($currentUserMusicsInList) + 1;
            $datas->setListOrder($listOrder);

            $datas->setUser($user);
        }
    }

    /**
     * If a music is removed from a list, the list order of the current user are rewritten
     *
     * @param ViewEvent $event
     * 
     * @return void
     */
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
