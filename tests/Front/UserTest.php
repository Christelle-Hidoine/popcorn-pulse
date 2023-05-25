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
        // dd($form);
        // dd($buttonCrawlerNode);

        $form['user[firstname]'] = 'Huzanna';
        $form['user[lastname]'] = 'Nouveau';
        $form['user[email]'] = 'huzanna@suzanna.com';
        $form['user[password]'] = "Nouveau@2";


        // TODO : soumettre le formulaire
        $client->submit($form);
        
        // TODO : verfier le code de retour
        // 200
        // $this->assertResponseIsSuccessful();
        // à la fin de l'insrtion de donnée veant d'un formulaire, on fait un redirect
        // ce qui correspond au code 302

        $this->assertResponseRedirects();
    }
}
