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
        // TODO : récupérer les films de la page favoris
        $movies = $movieRepository->findAll();
        $moviesFavorites = [];
        // TODO : stocker en session les favoris
        // ? où se trouve la session ? dans le cookies de la requete
        // ? où se trouve les informations qui proviennent de la requete ?
        // dans symfony il y un objet Request, tout comme il y a un objet Reponse
        // ? Comment on obtient cet objet Request ?
        // new Request();
        // ! ce n'est pas une bonne idée, car on devrait pas créer nous même une requete
        // il faut demander à symfony, c'est lui qui gère/reçoit la requete
        // Pour demander un objet à Symfony, il suffit de l'ajouter en argument de notre function
        // avec le type hinting Symfony va savoir de quel objet on a besoin
        // dd($request);
        // * cette façon de faire est utilisée dans plusieurs languages
        // * cela s'appele l'injection de dépendance
        
        $session = $request->getSession();
        // dd($session);
        // $session->set('favoris', "Vive les Radium");
        
        $themeSession = $session->get('theme', []);
        
        // TODO : récupérer les films favoris
        // on passe en paramètre un tableau vide au cas où on n'est aucun favoris sur la page à afficher
        // $favorite = $request->attributes->get("favoris$id");
        foreach ($movies as $movie) {
            $movieById = $movie->getId();
        }
        $moviesFavorites[] = $session->get("$movieById", []);
        // dump($moviesFavorites);

        // $moviesFavorite = $session->get('favoris', []);
        $sessionFav = $favorites->getFavoris($movies);

        // render() renvoie un contenu (résultat du fichier twig)
        return $this->render('front/favorites/index.html.twig', [
            'movies' => $moviesFavorites,
            'theme' => $themeSession,
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
        // TODO : j'ai besoin de l'identifiant du film à mettre en favoris
        // ? comment l'utilisateur me fournit l'ID ?
        // avec un paramètre de route : {id}
        // dd($id);

        // TODO : j'ai besoin des informations du film en question
        $movie = $movieRepository->find($id);

        // TODO : je veux mettre en session le film pour le garder en favoris
        // pour accéder à la session, il me faut la requete
        // on demande à Symfony l'objet Request
        // * injection de dépendance
        // $session = $request->getSession();
        $favorites->addFavorites($movie);
        
        // j'enregistre en session le film que l'utilisateur a indiqué comme favoris
        // dd($session);
        // $session->set("favoris$id", $movie);
        
        // ? je n'ai rien à afficher en particulier
        // je redirige l'utilisateur vers la page des favoris
        // càd vers une autre route
        // la méthode redirectToRoute() me fournit une Response
        // je renvois de suite cette response
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
    public function removeId($id, Request $request, FavoritesManager $favorites, MovieRepository $movieRepository): Response
    {
        $movieById = $movieRepository->find($id);
        // $session = $request->getSession();
        
        // $favorite = $session->get('favoris', []);
        // $favorite = $request->attributes->get("favoris$id");
        // dump($favorite);

        $favorites->removeFavorites($movieById);
        
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
    public function removeAll(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove('favoris');
        
        return $this->redirectToRoute("app_front_movie_favorites");
    }


}
