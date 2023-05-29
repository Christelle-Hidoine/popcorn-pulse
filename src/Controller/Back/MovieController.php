<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\Back\MovieType;
use App\Repository\MovieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/back/movie", name="app_back_movie_")
 * 
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $movies = $movieRepository->findAll();
        $movies = $paginator->paginate($movies, $request->query->getInt('page', 1), 10);
        return $this->render('back/movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            $this->addFlash(
                'success',
                'Bravo, votre film/série a bien été enregistré!'
            );

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     * 
     */
    public function show(?Movie $movie): Response
    {
        if ($movie === null){throw $this->createNotFoundException("ce film n'existe pas");}

            return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, ?Movie $movie, MovieRepository $movieRepository): Response
    {
        $this->denyAccessUnlessGranted("MOVIE_2030", $movie);
        
        if ($movie === null){throw $this->createNotFoundException("ce film n'existe pas");}

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            $this->addFlash(
                'success',
                'Bravo, votre film/série a bien été mis à jour!'
            );

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     * 
     */
    public function delete(Request $request, ?Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($movie === null){throw $this->createNotFoundException("ce film n'existe pas");}
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie, true);
        }

        return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
