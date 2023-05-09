<?php

namespace App\Controller\Back;

use App\Entity\Casting;
use App\Form\CastingType;
use App\Repository\CastingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/casting", name="app_back_casting_")
 */
class CastingController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CastingRepository $castingRepository, Request $request): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->render('back/casting/index.html.twig', [
            'castings' => $castingRepository->findAll(),
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, CastingRepository $castingRepository): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        $casting = new Casting();
        $form = $this->createForm(CastingType::class, $casting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $castingRepository->add($casting, true);

            return $this->redirectToRoute('app_back_casting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/casting/new.html.twig', [
            'casting' => $casting,
            'form' => $form,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Casting $casting, Request $request): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->render('back/casting/show.html.twig', [
            'casting' => $casting,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Casting $casting, CastingRepository $castingRepository): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        $form = $this->createForm(CastingType::class, $casting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $castingRepository->add($casting, true);
            $message = 'Le formulaire a bien été mis à jour.';
            dump($message);
            return $this->redirectToRoute('app_back_casting_index', ['message' => $message], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/casting/edit.html.twig', [
            'casting' => $casting,
            'form' => $form,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Casting $casting, CastingRepository $castingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$casting->getId(), $request->request->get('_token'))) {
            $castingRepository->remove($casting, true);
        }

        return $this->redirectToRoute('app_back_casting_index', [], Response::HTTP_SEE_OTHER);
    }
}
