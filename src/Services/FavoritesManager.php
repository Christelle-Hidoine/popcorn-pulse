<?php

namespace App\Services;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;

class FavoritesManager 
{
    /** @var RequestStack */
    private $request;
    private $favoris = [];

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
    public function addFavorites(Movie $movie)
    {
        $session = $this->request->getSession();
        $session->set("favoris", $movie);
        $this->favoris[] = $session->get("favoris", []);
    }

    // 2. removeFavorites
    public function removeFavorites(Movie $movie)
    {
        $session = $this->request->getSession();
        $removeFav = $session->remove("favoris", $movie);
        $this->favoris[] = $removeFav;
        dump($removeFav);
    }


    /**
     * Get the value of favoris
     */ 
    public function getFavoris()
    {
        return $this->favoris;
    }

    /**
     * Set the value of favoris
     *
     * @return  self
     */ 
    public function setFavoris($favoris)
    {
        $this->favoris = $favoris;

        return $this;
    }
}