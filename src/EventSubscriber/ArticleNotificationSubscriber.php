<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ArticleNotificationSubscriber implements EventSubscriberInterface
{
    // Définir les événements écoutés
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    // Méthode exécutée lorsqu'une réponse est envoyée
    public function onKernelResponse(ResponseEvent $event): void
    {
        // Ajouter un header personnalisé à toutes les réponses
        $event->getResponse()->headers->set(
            'X-Symfony-TP',
            'TP5-Finished'
        );
    }
}
