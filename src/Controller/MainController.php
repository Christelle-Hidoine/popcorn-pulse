<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * page par défaut/homepage affiche la liste de tous les films
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
        // dump($_SERVER);
        
        return $this->render("main/home.html.twig"/** pas de donnée pour l'instant */);
    }

    /**
     * page list affiche le résultat de la recherche
     *
     * @Route("/films", name="movie_list", methods={"GET"})
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->render("main/list.html.twig");
    }

    /**
     * page show affiche le détail d'un film 
     *
     * @Route("/films/{id}", name="show_movie", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function show($id): Response
    {
        return $this->render("main/show.html.twig", ['id' => $id]);
    }
}