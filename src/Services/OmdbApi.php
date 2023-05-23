<?php

namespace App\Services;

// composer require symfony/http-client

use App\Models\OmdbApiModel;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class OmdbApi 
{
    /**
     * Clé pour accéder à l'API
     *
     * @var string
     */
    private $apiKey;

    /**
     * Service client HTTP
     *
     * @var HttpClientInterface
     */
    private $client;

    /**
     * Service de désérialisation
     *
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(HttpClientInterface $client,SerializerInterface $serializerInterface, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->serializer = $serializerInterface;
    }

    public function fetch(string $title): OmdbApiModel
    {
        $response = $this->client->request(
            'GET',
            'http://www.omdbapi.com/', [
                // these values are automatically encoded before including them in the URL
                'query' => [
                    't' => $title,
                    'apiKey' => $this->apiKey,
                ],
            ]
        );

        // $statusCode = $response->getStatusCode();
        // $statusCode = 200
        // $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        // $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        
        /** @var OmdbApiModel $omdbApiModel */
        $omdbApiModel = $this->serializer->deserialize($content, OmdbApiModel::class, 'json');

        // dd($omdbApiModel);

        return $omdbApiModel;
    }
}