<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testMain(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/film/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'The Sopranos');

        
    }
}
