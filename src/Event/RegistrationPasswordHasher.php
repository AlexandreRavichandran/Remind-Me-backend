<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationPasswordHasher implements EventSubscriberInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
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
}
