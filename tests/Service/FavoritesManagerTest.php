<?php

// namespace App\Tests\Service;

// use App\Services\FavoritesManager;
// use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// class FavoritesManagerTest extends KernelTestCase
// {
//     public function testListFavoris(): void
//     {
//         $kernel = self::bootKernel();

//         $this->assertSame('test', $kernel->getEnvironment());

//         // TODO : tester le service FavoritesManager
//         // 1. récupérer le service
//         // on n'est pas dans Symfony = pas d'injection de dépendances
//         // Utilisation des raccourcis fournis par Symfony à PHPUnit (framework de test)
//         // * On demande au container (de services) de fournir notre service

//         /** @var FavoritesManager $favoritesManager */
//         $favoritesManager = static::getContainer()->get(FavoritesManager::class);
//         // dd($favoritesManager);

//         // TODO : le scénario
//         // 1. vérifier que la liste est vide au départ
//         $favorisList = $favoritesManager->listFavorites();
//         $this->assertCount(0, $favorisList);
//         // 2. ajouter un film
//         // 3. vérifier que la liste contient bien 1 film
//     }
// }
