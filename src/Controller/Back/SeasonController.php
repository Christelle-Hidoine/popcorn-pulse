<?php

namespace App\Controller\Back;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/season", name="app_back_season_")
 */
class SeasonController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(SeasonRepository $seasonRepository): Response
    {
        return $this->render('back/season/index.html.twig', [
            'seasons' => $seasonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, SeasonRepository $seasonRepository): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonRepository->add($season, true);

            $this->addFlash(
                'success',
                'Bravo, votre nouvelle saison a été enregistrée!'
            );
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()

            return $this->redirectToRoute('app_back_season_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/season/new.html.twig', [
            'season' => $season,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Season $season): Response
    {
        if ($season === null){throw $this->createNotFoundException("cette saison n'existe pas");}

        return $this->render('back/season/show.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Season $season, SeasonRepository $seasonRepository): Response
    {
        if ($season === null){throw $this->createNotFoundException("cette saison n'existe pas");}

        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonRepository->add($season, true);

            $this->addFlash(
                'success',
                'Bravo, votre saison a bien été mise à jour!'
            );

            return $this->redirectToRoute('app_back_season_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/season/edit.html.twig', [
            'season' => $season,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Season $season, SeasonRepository $seasonRepository): Response
    {
        if ($season === null){throw $this->createNotFoundException("cette saison n'existe pas");}

        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $seasonRepository->remove($season, true);
        }

        return $this->redirectToRoute('app_back_season_index', [], Response::HTTP_SEE_OTHER);
    }
}
