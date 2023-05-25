<?php

namespace App\Tests\Front;

use App\Form\Front\ReviewType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewTest extends WebTestCase
{
    public function testAddReviewWithoutSecurity(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/review/add/1');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Valider');

        $form = $buttonCrawlerNode->form();
        // dd($form);
        // dd($buttonCrawlerNode);

        $form['review[username]'] = 'Suzanna';
        $form['review[email]'] = 'suzanna@suzanna.com';
        $form['review[content]'] = "ce soir chez Suzanna, c'est soirée Disco";
        $form['review[rating]'] = 4;
        $form['review[reactions]'] = ["smile","cry"];
        $form['review[watchedAt]'] = '2023-05-23';

        // TODO : soumettre le formulaire
        $client->submit($form);
        
        // TODO : verfier le code de retour
        // 200
        // $this->assertResponseIsSuccessful();
        // à la fin de l'inscription de donnée veant d'un formulaire, on fait un redirect
        // ce qui correspond au code 302

        $this->assertResponseRedirects();

        
        // $this->assertSelectorTextContains('h2', 'Ajouter une critique sur le film ');
    }
}
