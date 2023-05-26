<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\Front\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
            $plaintextPassword = $user->getPassword();
            $passwordHashed = $passwordHasher->hashPassword($user,  $plaintextPassword);
            $user->setPassword($passwordHashed);
            $userRepository->add($user, true);

            $this->addFlash(
                'success',
                'Bravo ' . $user->getFirstname() . ' vous êtes bien enregistré!'
            );

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
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

            return $this->redirectToRoute('default', [], Response::HTTP_SEE_OTHER);
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

            $this->container->get('security.token_storage')->setToken(null);

            $userRepository->remove($user, true);
            
            $this->addFlash(
                'success',
                "Votre compte a bien été supprimé, revenez quand vous voulez."
            );

            return $this->redirectToRoute('default', [], Response::HTTP_SEE_OTHER);
        }
        
        
    }
}
