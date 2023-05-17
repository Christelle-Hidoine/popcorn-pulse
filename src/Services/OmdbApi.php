<?php

namespace App\Services;

// composer require symfony/http-client
use Symfony\Contracts\HttpClient\HttpClientInterface;


class OmdbApi 
{
    private $apiKey = "a93b767b";
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetch(string $title): array
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

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        
        return $content;
    }

    
}


