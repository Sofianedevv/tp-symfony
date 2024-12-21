<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserBannedSubscriber implements EventSubscriberInterface
{
    private $security;
    private $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        $request = $event->getRequest();

        // Ignorer les routes de login/logout et banned
        if (in_array($request->attributes->get('_route'), ['app_login', 'app_logout', 'app_banned'])) {
            return;
        }

        if ($user && in_array('ROLE_BANNED', $user->getRoles())) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_banned'));
            $event->setResponse($response);
        }
    }
} 