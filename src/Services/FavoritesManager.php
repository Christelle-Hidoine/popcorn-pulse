<?php

namespace App\Services;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;

class FavoritesManager 
{
    /** @var SessionInterface $session */
    private $request;

    /**
     * Injection de dépendance Request pour récupérer la session
     *
     * @param Request $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }


    // TODO : je sauvegarde en session les actions sur les favoris

    // 1. addFavorites 
    public function addFavorite(Movie $movie)
    {
        $session=$this->request->getSession();
        $favoriteSession = $session->get("favoris", []);
        $id = $movie->getId();
        $favoriteSession[$id] = $movie;
        $session->set("favoris", $favoriteSession);
    }

    // 2. listFavorites
    public function listFavorites()
    {
        $session=$this->request->getSession();
        return $session->get("favoris", []);
    }

    // 3. removeFavorites
    public function removeFavorite(Movie $movie)
    {
        $session=$this->request->getSession();
        $favorisList = $session->get("favoris", []);

        if (array_key_exists($movie->getId(), $favorisList)){
            // ? https://www.php.net/manual/en/function.unset.php
            unset($favorisList[$movie->getId()]);
            // met à jour la session
            $session->set("favoris", $favorisList);
        }
    }

    public function removeAll()
    {
        $session=$this->request->getSession();
        // on met un tableau vide pour purger nos favoris
        $session->set("favoris", []);
        // version plus bourine qui supprime directement la clé en session
        $session->remove("favoris");
    }

}