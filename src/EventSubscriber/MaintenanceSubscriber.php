<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{   
    private $maintenanceActive;

    /**
     * @param bool $argMaintenanceActive paramétrable dans le fichier services.yaml
     */
    public function __construct($argMaintenanceActive)
    {
        $this->maintenanceActive = $argMaintenanceActive;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        // TODO : rendre la modification conditionnelle
        if (!$this->maintenanceActive){
            // la maintenance est désactivé
            // on s'arrête là
            return;
        }
        // * request, avec pathInfo
        // va nous servir pour tester la route et exclure certaines routes
        $pathInfo = $event->getRequest()->getPathInfo();
        // dd($pathInfo); // /back/main
        if (strpos($pathInfo, "/back") === 0){
            // on est sur une route du back, on s'arrête là
            return;
        }
        // if (str_starts_with($pathInfo,"/back")){
        //     return;
        // }

        $response = $event->getResponse();
        // dump($response);
        $content = $response->getContent();
        // dd($content);
        $maintenance = str_replace('<div class="container">','<div class="container"><div class="alert alert-danger text-center">Maintenance prévue mardi 10 janvier à 17h00</div>', $content);
        // dd($maintenance);
        $response->setContent($maintenance);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
