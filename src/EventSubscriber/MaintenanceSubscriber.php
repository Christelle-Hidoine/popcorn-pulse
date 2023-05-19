<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        // dd($response);
        $maintenance = str_replace("<body%", '', '<body><div class="alert alert-danger">Maintenance prévue mardi 10 janvier à 17h00</div>');
        $response->setContent($maintenance);
        // dd($response);
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
