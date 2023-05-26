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
        if (!$this->maintenanceActive){
            return;
        }

        $pathInfo = $event->getRequest()->getPathInfo();
        if (strpos($pathInfo, "/back") === 0){
            return;
        }

        $response = $event->getResponse();
        $content = $response->getContent();
        $maintenance = str_replace('<div class="container">','<div class="container"><div class="alert alert-danger text-center">Maintenance prévue mardi 10 janvier à 17h00</div>', $content);
        $response->setContent($maintenance);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
