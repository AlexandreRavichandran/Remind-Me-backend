<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationPasswordHasher implements EventSubscriberInterface
{
    private $passwordHasher;
    private $jwtManager;
    private $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['hashPassword', EventPriorities::PRE_WRITE]
            ],
            KernelEvents::RESPONSE => [
                ['addToken', EventPriorities::POST_RESPOND]
            ]
        ];
    }

    /**
     * Hash the sent password before registrate the user
     *
     * @param ViewEvent $event
     * @return void
     */
    public function hashPassword(ViewEvent $event)
    {
        $registratedUser = $event->getControllerResult();
        if ($registratedUser instanceof User && $event->getRequest()->isMethod('POST')) {

            $hashedPassword = $this->passwordHasher->hashPassword($registratedUser, $registratedUser->getPassword());

            $registratedUser->setPassword($hashedPassword);
        }
    }

    /**
     * Add the JWT Token with the response, so the registered user is automatically logged
     *
     * @param ResponseEvent $event
     * @return void
     */
    public function addToken(ResponseEvent $event)
    {

        $responseCode = $event->getResponse()->getStatusCode();
        if ($responseCode === 201) {
            $response = json_decode($event->getResponse()->getContent());
            $user = $this->userRepository->findOneByEmail($response->email);
            $token = $this->jwtManager->create($user);
            $response->token = $token;
            $newResponse = json_encode($response);
            $event->getResponse()->setContent($newResponse);
        }
    }
}
