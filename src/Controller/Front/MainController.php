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
        $dataMovies = $movieRepository->findAll();
        $genres = $genreRepository->findAll();

        $dataMovies = $paginator->paginate($dataMovies, $request->query->getInt('page', 1),5);
    
        return $this->render("front/main/home.html.twig",
        [
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
        $movieById = $movieRepository->find($id);

        if ($movieById === null) {
            throw $this->createNotFoundException("Ce film n'existe pas");
        }

        $castingsWithDQL = $castingRepository->findByMovieOrderByCreditOrderWithPerson($movieById);
        $reviewByMovie = $reviewRepository->findBy(["movie" => $movieById], ["watchedAt" => "DESC"]);      
        $twigResponse = $this->render("front/main/show.html.twig",
        [
            "movieId" => $id,
            "movie" => $movieById,
            "allCasting" => $castingsWithDQL,
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
        MovieRepository $movieRepository,
        Request $request,
        PaginatorInterface $paginator): Response
    {
        $genreById = $genreRepository->find($id);
        $movieGenre = $movieRepository->findByGenre($id);
        $genreList = $genreRepository->findAll();

        if ($movieGenre === null) {
            throw $this->createNotFoundException("Ce genre n'a pas encore de films");
        }

        if ($genreById === null) {
            throw $this->createNotFoundException("Ce genre n'existe pas");
        }

        $movieGenre = $paginator->paginate($movieGenre, $request->query->getInt('page', 1),5);
        
        $twigResponse = $this->render("front/main/genre.html.twig",
        [
            "genreList" => $genreList,
            'movieGenre' => $movieGenre,
            'genreById' => $genreById
        ]);
        
        return $twigResponse;
    }
}