<?php
namespace Sasedev\Doctrine\BehaviorBundle\EventListener;

use Sasedev\Doctrine\Behavior\Loggable\LoggableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\EventListener\LoggerListener
 *
 * Sets the username from the security context by listening on kernel.request
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:38:03
 * @author Christophe Coevoet <stof@notk.org>
 */
class LoggerListener implements EventSubscriberInterface
{

    private $loggableListener;

    private $authorizationChecker;

    private $tokenStorage;

    public function __construct(LoggableListener $loggableListener, TokenStorageInterface $tokenStorage = null, AuthorizationCheckerInterface $authorizationChecker = null)
    {

        $this->loggableListener = $loggableListener;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;

    }

    /**
     *
     * @param RequestEvent $event
     * @internal
     */
    public function onKernelRequest(RequestEvent $event)
    {

        //if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
        if ($event->isMasterRequest()) {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->loggableListener->setUsername($token);
        }

    }

    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];

    }

}

