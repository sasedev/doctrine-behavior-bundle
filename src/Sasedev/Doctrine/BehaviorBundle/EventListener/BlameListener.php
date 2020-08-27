<?php
namespace Sasedev\Doctrine\BehaviorBundle\EventListener;

use Sasedev\Doctrine\Behavior\Blameable\BlameableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\EventListener\BlameListener
 *
 * Sets the username from the security context by listening on kernel.request
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:26:20
 * @author David Buchmann <mail@davidbu.ch>
 */
class BlameListener implements EventSubscriberInterface
{

    /**
     *
     * @var BlameableListener
     */
    private $blameableListener;

    /**
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     *
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(BlameableListener $blameableListener, TokenStorageInterface $tokenStorage = null, AuthorizationCheckerInterface $authorizationChecker = null)
    {

        $this->blameableListener = $blameableListener;
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

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType())
        {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker)
        {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->blameableListener->setUserValue($token->getUser());
        }

    }

    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];

    }

}

