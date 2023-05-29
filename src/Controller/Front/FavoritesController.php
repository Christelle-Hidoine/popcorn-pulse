<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Entity\User;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use App\Services\FavoritesManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FavoritesController extends AbstractController
{
    /**
     * Afficher le/les films en favoris
     * 
     * @Route("/favoris", name="app_front_movie_favorites")
     */
    public function favorites(): Response
    {
        // $moviesFavorites = [];
        // $moviesFavorites = $favorites->listFavorites();

        /** @var \App\Entity\User */
        $user = $this->getUser();

        $moviesFavorites = $user->getFavorite();

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
    public function add($id, MovieRepository $movieRepository, EntityManagerInterface $em): Response
    {
        /** @var App\Entity\User $user*/
        $user = $this->getUser();

        $movie = $movieRepository->find($id);

        if ($movie === null){ throw $this->createNotFoundException("ce favoris n'existe pas.");}
        if (!$user) {
            $this->redirectToRoute('app_login');
        } else {
            $movie->addFavorite($this->getUser());
            $title = $movie->getTitle();

            $em->persist($movie);
            $em->flush();

            $this->addFlash(
                'success',
                "$title a été ajouté à votre liste de favoris"
            );
        }

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
    public function remove($id, MovieRepository $movieRepository, EntityManagerInterface $em): Response
    {
        $movie = $movieRepository->find($id);
        if ($movie === null){ throw $this->createNotFoundException("ce favoris n'existe pas.");}

        $movie->removeFavorite($this->getUser());
        

        $title = $movie->getTitle();
        $em->persist($movie);
        $em->flush();

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
    public function removeAll(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        $moviesFavorites = $user->getFavorite();
        if ($moviesFavorites === null){ throw $this->createNotFoundException("ce favoris n'existe pas.");}

        foreach ($moviesFavorites as $movieFavorite) {
            $movieFavorite->removeFavorite($user);
            $em->persist($movieFavorite);
            $em->flush();
        }
        
        $this->addFlash(
            'success',
            "Les films ont été supprimés de votre liste de favoris"
        );

        return $this->redirectToRoute("app_front_movie_favorites");
    }


}
