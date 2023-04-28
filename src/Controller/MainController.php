<?php 

namespace App\Controller;

use App\Models\MovieModel;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * page par défaut/homepage affiche la liste de tous les films
     *
     * @Route("/", name="default", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function home(Request $request): Response
    {
        // TODO : récuperer la liste de tout les films
        // ! On utilise MovieModel tant que l'on a pas de BDD
        $allMovies = MovieModel::getAllMovies();
        dump($allMovies);

        // TODO : afficher la valeur de la session 'favoris'
        // ? pour accèder à la session, il me faut la requete
        // ? pour avoir la requete, je demande à Symfony : Injection de dépendance
        $session = $request->getSession();
        dump($session->get("favoris"));

        // la méthode render() prend 2 paramètres:
        // * le nom du fichier de vue que l'on veux utiliser
        // le chemin du fichier tiwg commence dans le dossier templates
        // * un tableau de donnée à afficher (optionnel)
        // cette méthode renvoit un objet Reponse, on va pouvoir le renvoyer
        // dump($_SERVER);
        
        return $this->render("main/home.html.twig",
        [
            // les données se passe par un tableau associatif
            // la clé du tableau deviendra le nom de la variable dans twig
            // TODO : fournir les données à twig
            "movieList" => $allMovies
        ]);
    }

    /**
     * page list affiche le résultat de la recherche
     *
     * @Route("/films", name="movie_search")
     *
     * @return Response
     */
    public function list(): Response
    {

        $movieSearch = MovieModel::getAllMovies();
        dump($movieSearch);

        return $this->render("main/list.html.twig",
            [
                'movieSearch' => $movieSearch
            ]
        );
    }

    /**
     * page show affiche le détail d'un film 
     *
     * @Route("/film/{id}", name="movie_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function show($id, MovieRepository $movieRepository): Response
    {
        // TODO : récuperer le film avec son id
        // $movieById = MovieModel::getMovie($id);
        // récupération des données sur la BDD

        $movieById = $movieRepository->find($id);
        // dd($movieById);
        
        $twigResponse = $this->render("main/show.html.twig",
        [
            "movieId" => $id,
            // TODO fournir le film à ma vue
            "movieForTwig" => $movieById
        ]);
        

        return $twigResponse;
    }
}