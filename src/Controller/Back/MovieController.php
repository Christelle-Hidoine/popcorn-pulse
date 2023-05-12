<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * en déclarant la route ici, on préfixe toutes les routes du controller
 * @Route("/back/movie", name="app_back_movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('back/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository): Response
    {
        // TODO : on applique la sécurité
        // il faut le ROLE_ADMIN pour acceder ici
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
     * rajouter un ? dans les arguments pour autoriser la valeur Null sur l'objet de l'entité
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
        $this->denyAccessUnlessGranted("MOVIE_1430", $movie);
        
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
