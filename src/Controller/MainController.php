<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * page par défaut
     *
     * @Route("/", name="default", methods={"GET"})
     *
     * @return Response
     */
    public function home(): Response
    {
        // la méthode render() prend 2 paramètres:
        // * le nom du fichier de vue que l'on veux utiliser
        // le chemin du fichier tiwg commence dans le dossier templates
        // * un tableau de donnée à afficher (optionnel)
        // cette méthode renvoit un objet Reponse, on va pouvoir le renvoyer
        dump($_SERVER);
        return $this->render("main/home.html.twig"/** pas de donnée pour l'instant */);
    }

    /**
     * page favorites
     *
     * @Route("/favorites", name="movie_favorites", methods={"GET"})
     *
     * @return Response
     */
    public function favorites(): Response
    {
        return $this->render("favorites.html.twig");
    }

    /**
     * page list
     *
     * @Route("/movies", name="movie_list", methods={"GET"})
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->render("list.html.twig");
    }

    /**
     * page show
     *
     * @Route("/movie/{id}", name="show_movie", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->render("show.html.twig");
    }
}