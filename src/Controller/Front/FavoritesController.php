<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Services\FavoritesManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * Afficher le/les films en favoris
     * 
     * @Route("/favoris", name="app_front_movie_favorites")
     */
    public function favorites(Request $request, FavoritesManager $favorites, MovieRepository $movieRepository): Response
    {
        $moviesFavorites = [];
        $moviesFavorites = $favorites->listFavorites();

        return $this->render('front/favorites/index.html.twig', [
            'movies' => $moviesFavorites,
        ]);
    }

    /**
     * ajout d'un film en favoris
     * 
     * @Route("/favoris/add/{id}", name="app_front_add_favorites", requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function add($id, MovieRepository $movieRepository, Request $request, FavoritesManager $favorites): Response
    {
        $movie = $movieRepository->find($id);

        if ($movie === null){ throw $this->createNotFoundException("ce favoris n'existe pas.");}

        $favorites->addFavorite($movie);
        $title = $movie->getTitle();

        $this->addFlash(
            'success',
            "$title a été ajouté à votre liste de favoris"
        );
        
        return $this->redirectToRoute('app_front_movie_favorites');

    }

    /**
     * supprime un film en favoris
     *
     * @Route("/favoris/delete/{id}", name="app_front_delete_favorites_id", requirements={"id"="\d+"})
     *
     * @param int $id id du film 
     * @param Request $request injection de dépendance pour récupérer la session
     * @return Response
     */
    public function remove($id, FavoritesManager $favorites, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);
        if ($movie === null){ throw $this->createNotFoundException("ce favoris n'existe pas.");}
        
        $favorites->removeFavorite($movie);
        $title = $movie->getTitle();

        $this->addFlash(
            'success',
            "$title a été supprimé de votre liste de favoris"
        );
        
        return $this->redirectToRoute("app_front_movie_favorites");
    }

    /**
     * supprime tous les films en favoris
     *
     * @Route("/favoris/delete", name="app_front_delete_favorites")
     *
     * @param Request $request injection de dépendance pour récupérer la session
     * @return Response
     */
    public function removeAll(FavoritesManager $favorite): Response
    {
        $favorite->removeAll();
        
        $this->addFlash(
            'success',
            "Les films ont été supprimés de votre liste de favoris"
        );

        return $this->redirectToRoute("app_front_movie_favorites");
    }


}
