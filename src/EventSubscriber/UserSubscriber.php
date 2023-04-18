<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function addUser(ViewEvent $event): void
    {
        if ($event->isMainRequest() && $event->getRequest()->isMethod('POST') && '/api/users' == $event->getRequest()->getPathInfo()) {
            $user = $event->getControllerResult();
            $user->setBirthdayDate(new \DateTime('1980-01-01'));
            $user->setClient($this->tokenStorage->getToken()->getUser());
            $event->setControllerResult($user);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           KernelEvents::VIEW => ['addUser', EventPriorities::PRE_WRITE],
        ];
    }
}
