<?php

namespace App\Services;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;

class FavoritesManager 
{
    /** @var SessionInterface $session */
    private $session;

    /**
     * Injection de dépendance Request pour récupérer la session
     *
     * @param Request $request
     */
    public function __construct(RequestStack $request)
    {
        $this->session = $request->getSession();
    }


    // TODO : je sauvegarde en session les actions sur les favoris

    // 1. addFavorites 
    public function addFavorite(Movie $movie)
    {
        $favoriteSession = $this->session->get("favoris", []);
        $id = $movie->getId();
        $favoriteSession[$id] = $movie;
        $this->session->set("favoris", $favoriteSession);
        
    }

    // 2. listFavorites
    public function listFavorites()
    {
        return $this->session->get("favoris", []);
    }

    // 3. removeFavorites
    public function removeFavorite(Movie $movie)
    {
        $favorisList = $this->session->get("favoris", []);

        if (array_key_exists($movie->getId(), $favorisList)){
            // ? https://www.php.net/manual/en/function.unset.php
            unset($favorisList[$movie->getId()]);
            // met à jour la session
            $this->session->set("favoris", $favorisList);
        }
    }

    public function removeAll()
    {
        // on met un tableau vide pour purger nos favoris
        $this->session->set("favoris", []);
        // version plus bourine qui supprime directement la clé en session
        $this->session->remove("favoris");
    }

}