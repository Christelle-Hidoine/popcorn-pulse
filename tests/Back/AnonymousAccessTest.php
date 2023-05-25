<?php

namespace App\Tests\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonymousAccessTest extends WebTestCase
{
    /**
     * Routes en GET pour Anonymous
     */
    public function testPageGetIsRedirect()
    {
        $client = self::createClient();
        $client->request('GET', '/back/main');
        
        $this->assertResponseRedirects();
    }
}
