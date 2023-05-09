<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * en déclarant la route ici, on préfixe toutes les routes du controller
 * @Route("/back/movie", name="app_back_movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository, Request $request): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->render('back/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     * rajouter un ? dans les arguments pour autoriser la valeur Null sur l'objet de l'entité
     */
    public function show(?Movie $movie, Request $request): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        if ($movie === null){throw $this->createNotFoundException("ce film n'existe pas");}

            return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ?Movie $movie, MovieRepository $movieRepository): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        if ($movie === null){throw $this->createNotFoundException("ce film n'existe pas");}

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
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
