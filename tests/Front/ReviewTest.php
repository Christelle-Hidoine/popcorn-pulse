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

        $form['review[username]'] = 'Suzanna';
        $form['review[email]'] = 'suzanna@suzanna.com';
        $form['review[content]'] = "ce soir chez Suzanna, c'est soirée Disco";
        $form['review[rating]'] = 4;
        $form['review[reactions]'] = ["smile", false, "sleep"];
        $form['review[watchedAt]'] = '2023-05-23';

        $client->submit($form);

        $this->assertResponseRedirects();

    }
}
