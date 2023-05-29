<?php

namespace App\Services;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class FavoritesManager 
{
    /** @var SessionInterface $session */
    private $request;

    /**
     * Injection de dépendance Request pour récupérer la session
     *
     * @param Request $request
     */
    public function __construct(RequestStack $request, Security $security)
    {
        $this->request = $request;
    }

    public function addFavorite(Movie $movie)
    {
        $session=$this->request->getSession();
        $favoriteSession = $session->get("favoris", []);
        $id = $movie->getId();
        $favoriteSession[$id] = $movie;
        $session->set("favoris", $favoriteSession);
    }

    public function listFavorites()
    {
        $session=$this->request->getSession();
        return $session->get("favoris", []);
    }

    public function removeFavorite(Movie $movie)
    {
        $session=$this->request->getSession();
        $favorisList = $session->get("favoris", []);

        if (array_key_exists($movie->getId(), $favorisList)){
            unset($favorisList[$movie->getId()]);
            $session->set("favoris", $favorisList);
        }
    }

    public function removeAll()
    {
        $session=$this->request->getSession();
        $session->set("favoris", []);
        $session->remove("favoris");
    }

}