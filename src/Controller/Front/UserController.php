<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\Front\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/front/user", name="app_front_user_")
 */
class UserController extends AbstractController
{
    // /**
    //  * @Route("/", name="index", methods={"GET"})
    //  */
    // public function index(UserRepository $userRepository): Response
    // {
    //     return $this->render('front/user/index.html.twig', [
    //         'users' => $userRepository->findAll(),
    //     ]);
    // }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(UserPasswordHasherInterface $passwordHasher, Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            // on récupère le password non hashé du form
            $plaintextPassword = $user->getPassword();
            // on hash le password
            $passwordHashed = $passwordHasher->hashPassword($user,  $plaintextPassword);
            // on injecte dans la BDD
            $user->setPassword($passwordHashed);
            $userRepository->add($user, true);

            $this->addFlash(
                'success',
                'Bravo ' . $user->getFirstname() . ' vous êtes bien enregistré!'
            );

            return $this->redirectToRoute('app_front_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('front/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_front_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_front_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
