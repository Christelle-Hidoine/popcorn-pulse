<?php

namespace App\Controller\Back;

use App\Entity\Genre;
use App\Form\Back\GenreType;
use App\Repository\GenreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/genre")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/", name="app_back_genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $genres = $genreRepository->findAll();
        $genres = $paginator->paginate($genres, $request->query->getInt('page', 1),10);
        return $this->render('back/genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/new", name="app_back_genre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GenreRepository $genreRepository): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreRepository->add($genre, true);

            return $this->redirectToRoute('app_back_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_genre_show", methods={"GET"})
     */
    public function show(Genre $genre): Response
    {
        return $this->render('back/genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_genre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreRepository->add($genre, true);

            return $this->redirectToRoute('app_back_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_genre_delete", methods={"POST"})
     */
    public function delete(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $genreRepository->remove($genre, true);
        }

        return $this->redirectToRoute('app_back_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
