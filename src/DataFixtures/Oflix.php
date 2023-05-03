<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class Oflix extends Fixture
{
    /**
     * Création de donnée
     *
     * @param ObjectManager $manager Equivalent à l'EntityManager
     */
    public function load(ObjectManager $manager): void
    {
        $genres = ["Action", "Animation", "Aventure", "Comédie", "Dessin Animé", "Documentaire", "Drame", "Espionnage", "Famille", "Fantastique", "Historique", "Policier", "Romance", "Science-fiction", "Thriller", "Western"];

        foreach($genres as $genre) {
            // TODO : créer des genres
            // TODO : en créer 1 puis on fait une boucle
            // * faire un new
            $newGenre = new Genre();

            // * remplir les propriétés
            $newGenre->setName($genre);

            // * appeler la méthode persist() avec notre entité
            // on demande la persistance de l'objet
            $manager->persist($newGenre);
        }    
        
        // TODO : créer les 2 types : films et série
        $types = ["Film", "Série"];

        foreach($types as $type) {
            $newType = new Type();
            $newType->setName($type);
            $manager->persist($newType);
        }

        // TODO : créer 10 movies
        // je créer un tableau pour conserver les instances des movies
        // et les utiliser ultérieurement
        $allMovies = [];

        for ($i=1; $i <= 10; $i++) {
            // 1. 
            $newMovie = new Movie();

            // 2.
            $newMovie->setTitle("Movie #" . $i);
            $newMovie->setSynopsis("lorem ipsum");
            $newMovie->setSummary("lorem ipsum");

            //Generate a timestamp using mt_rand.
            $timestamp = mt_rand(1, time());
            //Format that timestamp into a readable date string.
            $randomDate = date("Y/m/d", $timestamp);
            $newMovie->setReleaseDate(new DateTimeInterface($randomDate));

            // un float aléatoire rating
            $randomRating = mt_rand(0,5)/0.2;
            $newMovie->setRating($randomRating);
            $newMovie->setCountry("pays");
            // ? https://picsum.photos/
            $newMovie->setPoster("https://picsum.photos/200/300");

            // 3.
            $manager->persist($newMovie);

            $allMovies[] = $newMovie;
        }    

        // TODO associer entre 1 et 3 genres pour chaque movie
        // foreach ($allMovies as $movie) {
            // on decide du nombre de genres
        //     $randomNbGenre = mt_rand(1,3);

        //     for ($i=1; $i < $randomNbGenre; $i++) {
        //         $randomIndexGenre = mt_rand(0, count($genres) -1);
        //         $randomGenre = $genres[$randomIndexGenre];
        //         /** @var Movie $movie */
        //         $movie->addGenre($randomGenre);
        //     }
        // }

        // * appeler la méthode flush()
        // c'est ici que les requetes SQL sont exécutées
        $manager->flush();

        //puis ajouter dans la base de données avec la commande : bin/console doctrine:fixture:load
    }
}
