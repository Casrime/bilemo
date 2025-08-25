<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function addUser(ViewEvent $viewEvent): void
    {
        if ($viewEvent->isMainRequest() && $viewEvent->getRequest()->isMethod('POST') && '/api/users' === $viewEvent->getRequest()->getPathInfo()) {
            /** @var User $user */
            $user = $viewEvent->getControllerResult();
            $user->setBirthdayDate(new \DateTime('1980-01-01'));
            /** @var TokenInterface|null $token */
            $token = $this->tokenStorage->getToken();
            if (null === $token) {
                return;
            }
            /** @var Client $client */
            $client = $token->getUser();
            $user->setClient($client);
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
