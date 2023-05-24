<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CoreApiController extends AbstractController
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function json200($data, array $groups): JsonResponse
    {
        return $this->json(
            // les données
            $data, 
            // le code de retour : 200 par défaut
            Response::HTTP_OK,
            // les entêtes HTTP, on ne s'en sert pas : []
            [],
            // le contexte de serialisation : les groupes
            [
                // on précise les groupes                
                "groups" => 
                // c'est un tableau indexé, avec les noms de groupes
                $groups
            ]
        );
    }

    public function json201($data, array $groups): JsonResponse
    {
        return $this->json(
            // les données
            $data, 
            // le code de retour : 200 par défaut
            Response::HTTP_CREATED,
            // les entêtes HTTP, on ne s'en sert pas : []
            [],
            // le contexte de serialisation : les groupes
            [
                // on précise les groupes                
                "groups" => 
                // c'est un tableau indexé, avec les noms de groupes
                $groups
            ]
        );
    }

}
