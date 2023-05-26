<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function testAddUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'front/user/new');
        $this->assertResponseIsSuccessful();
 
        $buttonCrawlerNode = $crawler->selectButton('Sauvegarder');

        $form = $buttonCrawlerNode->form();

        $form['user[firstname]'] = 'Huzanna';
        $form['user[lastname]'] = 'Nouveau';
        $form['user[email]'] = 'huzanna@suzanna.com';
        $form['user[password]'] = "Nouveau@2";

        $client->submit($form);
        
        $this->assertResponseRedirects();
    }
}
