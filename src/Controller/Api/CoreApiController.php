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

    public function json200($data, array $groups): JsonResponse
    {
        return $this->json(
            $data, 
            Response::HTTP_OK,
            [],
            [
                "groups" => 
                $groups
            ]
        );
    }

    public function json201($data, array $groups): JsonResponse
    {
        return $this->json(
            $data, 
            Response::HTTP_CREATED,
            [],
            [
                "groups" => 
                $groups
            ]
        );
    }

}
