<?php

namespace App\Tests\Front;

use App\Form\Front\ReviewType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/review/add/1');
        
        $buttonCrawlerNode = $crawler->selectButton('Valider');

        $form = $buttonCrawlerNode->form();
        $nameForm = $form->getName();
        // dd($form);

        $form[$nameForm['username']] = 'Boris';
        $form[$nameForm['email']] = 'boris@boris.com';
        $form[$nameForm['content']] = 'waouw';
        $form[$nameForm['rating']] = 3;
        $form[$nameForm['reactions']] = ['cry','smile'];
        $form[$nameForm['watchedAt']] = '2023-05-23';

        $client->submit($form);
        $form->getValues();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
