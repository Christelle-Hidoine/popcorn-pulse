<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * Afficher le/les films en favoris
     * 
     * @Route("/favoris", name="movie_favorites")
     */
    public function favorites(Request $request): Response
    {
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
        // en PHP, sans symfony : 
        // $_SESSION["favoris"] = "Vive les Radium";
        dump($session);
        
        // TODO : récupérer les films favoris
        // on passe en paramètre un tableau vide au cas où on n'est aucun favoris sur la page à afficher
        $moviesFavorites = $session->get('favoris', []);
        // dd($moviesFavorites);

        // render() renvoie un contenu (résultat du fichier twig)
        return $this->render('favorites/index.html.twig', [
            'movie' => $moviesFavorites,
        ]);
    }

    /**
     * ajout d'un film en favoris
     * 
     * @Route("/favoris/add/{id}", name="add_favorites", requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function add($id, MovieRepository $movieRepository, Request $request): Response
    {
        // TODO : j'ai besoin de l'identifiant du film à mettre en favoris
        // ? comment l'utilisateur me fournit l'ID ?
        // avec un paramètre de route : {id}
        // dd($id);

        // TODO : j'ai besoin des informations du film en question
        // je vais demander à la classe MovieModel de me donner les informations de ce film
        // $movie = MovieModel::getMovie($id);
        $movie = $movieRepository->find($id);

        // TODO : je veux mettre en session le film pour le garder en favoris
        // pour accéder à la session, il me faut la requete
        // on demande à Symfony l'objet Request
        // * injection de dépendance
        $session = $request->getSession();

        // j'enregistre en session le film que l'utilisateur a indiqué comme favoris
        $session->set("favoris", $movie);
        // dd($session);

        // ? je n'ai rien à afficher en particulier
        // je redirige l'utilisateur vers la page des favoris
        // càd vers une autre route
        // la méthode redirectToRoute() me fournit une Response
        // je renvois de suite cette response
        return $this->redirectToRoute('movie_favorites');


    }
}
