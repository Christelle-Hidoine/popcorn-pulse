<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController

{
    /**
     * page favoris d'un utilisateur
     *
     * @Route("/favoris", name="movie_favorites", methods={"GET"})
     *
     * @return Response
     */
    public function favorites(): Response
    {
        return $this->render("favorites.html.twig");
    }
}