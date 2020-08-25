<?php
namespace Sasedev\Doctrine\BehaviorBundle\EventListener;

use Sasedev\Doctrine\Behavior\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\EventListener\LocaleListener
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:34:56
 * @author Christophe COEVOET
 */
class LocaleListener implements EventSubscriberInterface
{

    private $translatableListener;

    public function __construct(TranslatableListener $translatableListener)
    {

        $this->translatableListener = $translatableListener;

    }

    /**
     *
     * @param RequestEvent $event
     * @internal
     */
    public function onKernelRequest(RequestEvent $event)
    {

        $this->translatableListener->setTranslatableLocale($event->getRequest()
            ->getLocale());

    }

    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];

    }

}

