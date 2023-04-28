<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;
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

        // * appeler la méthode flush()
        // c'est ici que les requetes SQL sont exécutées
        $manager->flush();

        //puis ajouter dans la base de données avec la commande : bin/console doctrine:fixture:load
    }
}
