<?php

namespace App\Controller\Back;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/person", name="app_back_person_")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PersonRepository $personRepository, Request $request): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->render('back/person/index.html.twig', [
            'people' => $personRepository->findAll(),
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, PersonRepository $personRepository): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->add($person, true);

            return $this->redirectToRoute('app_back_person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/person/new.html.twig', [
            'person' => $person,
            'form' => $form,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Person $person, Request $request): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->render('back/person/show.html.twig', [
            'person' => $person,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Person $person, PersonRepository $personRepository): Response
    {
        // récupération du thème avant envoi à la vue
        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->add($person, true);

            return $this->redirectToRoute('app_back_person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
            'theme' => $themeSession,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Person $person, PersonRepository $personRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $personRepository->remove($person, true);
        }

        return $this->redirectToRoute('app_back_person_index', [], Response::HTTP_SEE_OTHER);
    }
}
