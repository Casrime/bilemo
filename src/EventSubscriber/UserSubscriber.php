<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function addUser(ViewEvent $viewEvent): void
    {
        if ($viewEvent->isMainRequest() && $viewEvent->getRequest()->isMethod('POST') && '/api/users' === $viewEvent->getRequest()->getPathInfo()) {
            $user = $viewEvent->getControllerResult();
            $user->setBirthdayDate(new \DateTime('1980-01-01'));
            $user->setClient($this->tokenStorage->getToken()->getUser());
            $viewEvent->setControllerResult($user);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['addUser', EventPriorities::PRE_WRITE],
        ];
    }
}
