<?php 

namespace App\Controller;

use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class MainController extends AbstractController
{
    /**
     * page par défaut/homepage affiche la liste de tous les films
     *
     * @Route("/", name="default", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function home(MovieRepository $movieRepository, GenreRepository $genreRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // TODO : récuperer la liste de tout les films
        // ! On utilise MovieModel tant que l'on a pas de BDD
        // $allMovies = MovieModel::getAllMovies();
        // dump($allMovies);
        $dataMovies = $movieRepository->findAll();
        $genres = $genreRepository->findAll();

        // TODO : faire la pagination
        $movies = $paginator->paginate($dataMovies, $request->query->getInt('page', 1),5);

        // TODO : afficher la valeur de la session 'favoris'
        // ? pour accèder à la session, il me faut la requete
        // ? pour avoir la requete, je demande à Symfony : Injection de dépendance
        // $session = $request->getSession();
        // dump($session->get("favoris"));

        // la méthode render() prend 2 paramètres:
        // * le nom du fichier de vue que l'on veux utiliser
        // le chemin du fichier tiwg commence dans le dossier templates
        // * un tableau de donnée à afficher (optionnel)
        // cette méthode renvoit un objet Reponse, on va pouvoir le renvoyer
        // dump($_SERVER);
        
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);
        dump($dataMovies);
        return $this->render("main/home.html.twig",
        [
            // les données se passe par un tableau associatif
            // la clé du tableau deviendra le nom de la variable dans twig
            // TODO : fournir les données à twig
            "movieList" => $dataMovies,
            "genreList" => $genres,
            "theme" => $themeSession,
        ]);
    }

    /**
     * page search affiche le résultat de la recherche
     *
     * @Route("/films", name="movie_search")
     *
     * @return Response
     */
    public function search(MovieRepository $movieRepository, GenreRepository $genreRepository, Request $request): Response
    {
        $genres = $genreRepository->findAll();

        $search = $request->query->get('search', "");
        $movies = $movieRepository->findByMovieTitle($search);
        dump($search);

        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->render("main/search.html.twig",
            [
                'movieSearch' => $movies,
                'genreList' => $genres,
                'theme' => $themeSession,
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
    public function show($id, MovieRepository $movieRepository, CastingRepository $castingRepository, Request $request): Response
    {
        // TODO : récuperer le film avec son id
        $movieById = $movieRepository->find($id);
        // dd($movieById);

        // ! ERREUR $movieById == null si le film n'a pas été trouvé en BDD
        if ($movieById === null) {
            throw $this->createNotFoundException("Ce film n'existe pas");
        }

        // TODO : récuperer les castings du film, trié par creditOrder
        // BBD : Repository, Casting : CastingRepository : Injection de dépendance
        $allCastingByMovie = $castingRepository->findBy(
            // * critere de recherche
            // on manipule TOUJOURS des objets
            // donc on parle propriété : movie (de l'objet Casting)
            // cette propriété doit être égale à l'objet $movie
            [
                "movies" => $movieById
            ],
            // * orderBy
            // on manipule TOUJOURS des objets
            // on donne la propriété sur laquelle on trie
            // en valeur, on donne le type de tri : ASC/DESC
            [
                "creditOrder" => "ASC"
            ]
        );

        $castingsWithDQL = $castingRepository->findByMovieOrderByCreditOrderWithPerson($movieById);

        dump($allCastingByMovie);

        $session = $request->getSession();
        $themeSession = $session->get('theme', []);
        
        $twigResponse = $this->render("main/show.html.twig",
        [
            "movieId" => $id,
            // TODO fournir le film à ma vue
            "movieForTwig" => $movieById,
            // TODO fournir le casting à ma vue
            "allCasting" => $castingsWithDQL,
            // TODO fournir le thème à ma vue
            'theme' => $themeSession,
        ]);
        

        return $twigResponse;
    }
}