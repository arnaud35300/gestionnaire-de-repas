<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserDeleteSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelResponse(ResponseEvent $event)
    {

        // On s'assure qu'on est sur la requête principale
        // cf : https://symfony.com/doc/current/components/http_kernel.html#sub-requests
        if (!$event->isMasterRequest())
            return;

        // La réponse se trouve dans $event->getResponse()
        $response = $event->getResponse();

        // Est-on dans le Profiler ? Si oui on sort
        // PS : l'en-tête 'X-Debug-Token' est présente sur les pages en mode dev
        // sauf sur les pages du Profiler
        if (!$response->headers->has('X-Debug-Token'))
            return;

        // L'événement ResponseEvent $event contient notamment la réponse qui va être envoyée
        // cf : https://symfony.com/doc/current/reference/events.html#kernel-response
        // On est à l'étape 7 du parcours
        // cf schéma : https://symfony.com/doc/current/components/http_kernel.html#the-workflow-of-a-request
        // A ce stade on peut modifier la réponse qui va être envoyée


        if ($this->tokenStorage->getToken()->getUser() === 'anon.' || $this->tokenStorage->getToken()->getUser()->getStatus())
            return;

        $content = $response->getContent();

        // cf : https://www.php.net/manual/fr/function.str-replace.php
        $newContent = str_replace('<header>', '<div class="border-b-2 text-white font-bold border-red-800 text-center w-full bg-red-500"alt="profile page"><span class="text-red-800">Warning !</span> Your account will be deleted unless 24h</div>', $content);

        // On assigne le nouveau contenu à la réponse
        $response->setContent($newContent);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => [
                // On modifie la priorité de notre écouteur pour le placer
                // après la mise en place des en-têtes
                ['onKernelResponse', -115],
            ],
        ];
    }
}
