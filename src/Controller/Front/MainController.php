<?php 

namespace App\Controller\Front;

use App\Entity\Review;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("PUBLIC_ACCESS")
 */
class MainController extends AbstractController
{
    /**
     * page par défaut/homepage affiche la liste de tous les films
     *
     * @Route("/", name="default", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function home(MovieRepository $movieRepository, 
        GenreRepository $genreRepository, 
        Request $request, 
        PaginatorInterface $paginator
        ): Response
    {
        // TODO : récuperer la liste de tous les films
        $dataMovies = $movieRepository->findAll();
        $genres = $genreRepository->findAll();

        // TODO : faire la pagination
        $dataMovies = $paginator->paginate($dataMovies, $request->query->getInt('page', 1),5);
    
        // la méthode render() prend 2 paramètres:
        // * le nom du fichier de vue que l'on veux utiliser
        // le chemin du fichier tiwg commence dans le dossier templates
        // * un tableau de donnée à afficher (optionnel)
        // cette méthode renvoie un objet Reponse, on va pouvoir le renvoyer
        return $this->render("front/main/home.html.twig",
        [
            // la clé du tableau deviendra le nom de la variable dans twig
            // TODO : fournir les données à twig
            "movieList" => $dataMovies,
            "genreList" => $genres,
        ]);
    }

    /**
     * page search affiche le résultat de la recherche
     *
     * @Route("/films", name="app_front_movie_search")
     *
     * @return Response
     */
    public function search(MovieRepository $movieRepository, 
        GenreRepository $genreRepository, 
        Request $request, 
        PaginatorInterface $paginator): Response
    {
        $genres = $genreRepository->findAll();

        $search = $request->query->get('search', "");
        $movies = $movieRepository->findByMovieTitle($search);
        // dump($search);

        // TODO : faire la pagination
        $movies = $paginator->paginate($movies, $request->query->getInt('page', 1),5);

        return $this->render("front/main/search.html.twig",
            [
                'movieSearch' => $movies,
                'genreList' => $genres,
            ]
        );
    }

    /**
     * page show affiche le détail d'un film 
     *
     * @Route("/film/{id}", name="app_front_movie_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function show($id, 
        MovieRepository $movieRepository, 
        CastingRepository $castingRepository, 
        ReviewRepository $reviewRepository
        ): Response
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
            // donc on parle propriété : movies (de l'objet Casting)
            // cette propriété doit être égale à l'objet $movieById
            [
                "movies" => $movieById
            ],
            // * orderBy
            // on donne la propriété sur laquelle on trie
            // en valeur, on donne le type de tri : ASC/DESC
            [
                "creditOrder" => "ASC"
            ]
        );
        // autre méthode pour trier les castings en passant par castingRepository
        $castingsWithDQL = $castingRepository->findByMovieOrderByCreditOrderWithPerson($movieById);

        // TODO : récupérer les critiques par film - affichage des 5 dernières critiques
        $reviewByMovie = $reviewRepository->findBy(["movie" => $movieById], ["watchedAt" => "DESC"]);
        // dump($reviewByMovie);
        
        $twigResponse = $this->render("front/main/show.html.twig",
        [
            "movieId" => $id,
            // TODO fournir le film à ma vue
            "movie" => $movieById,
            // TODO fournir le casting à ma vue
            "allCasting" => $castingsWithDQL,
            // TODO fournir les critiques à ma vue
            'reviews' => $reviewByMovie
        ]);
        
        return $twigResponse;
    }

    /**
     * page show affiche les films par genre 
     *
     * @Route("/genre/{id}", name="app_front_genre_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function genreShow($id, 
        GenreRepository $genreRepository, 
        Request $request,
        PaginatorInterface $paginator): Response
    {
        // TODO : récuperer le film avec son id
        $genreById = $genreRepository->find($id);
        $movieGenre = $genreById->getMovies();
        $genreList = $genreRepository->findAll();
        dump($genreById);

        // ! ERREUR $genreById->getMovies() == null si le film n'a pas été trouvé en BDD
        if ($genreById->getMovies() === null) {
            throw $this->createNotFoundException("Ce genre n'a pas encore de films");
        }

        // TODO : faire la pagination
        $movieGenre = $paginator->paginate($genreById->getMovies(), $request->query->getInt('page', 1),5);
        
        $twigResponse = $this->render("front/main/genre.html.twig",
        [
            // TODO fournir la liste des genres à ma vue
            "genreList" => $genreList,
            // TODO fournir les films correspondants à ces genres
            'movieGenre' => $movieGenre,
            // TODO fournir le data du genre
            "genreById" => $genreById,
        ]);
        
        return $twigResponse;
    }
}