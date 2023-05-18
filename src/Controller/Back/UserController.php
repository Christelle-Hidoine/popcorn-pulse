<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\Back\UserEditType;
use App\Form\Back\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/back/user", name="app_back_user_")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(UserPasswordHasherInterface $passwordHasher, Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère le password non hashé du form
            $plaintextPassword = $user->getPassword();
            // on hash le password
            $passwordHashed = $passwordHasher->hashPassword($user,  $plaintextPassword);
            // on injecte dans la BDD
            $user->setPassword($passwordHashed);
            $userRepository->add($user, true);

            $this->addFlash(
                'success',
                'Bravo, le nouvel user a bien été enregistré!'
            );

            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(User $user): Response
    {
        if ($user === null){throw $this->createNotFoundException("ce user n'existe pas");}
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(UserPasswordHasherInterface $passwordHasher, Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($user === null){throw $this->createNotFoundException("ce user n'existe pas");}
        
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // il nous faut le mot de passe : 
            // * on le récupère depuis la requete car on a désactivé la mise à jour auto par le formulaire
            $plainPassword = $request->request->get("user_edit")["password"];
            dump($request->request->get('user_edit'));
            // dd($plainPassword);
            // si mdp complété = on ne récupère pas le mdp 
            if (!empty($plainPassword)){
                // je hash le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                // * j'oublie pas de mettre à jour mon objet
                $user->setPassword($hashedPassword);     
            }
            
            $userRepository->add($user, true);

            $this->addFlash(
                'success',
                'Bravo, votre user a bien été mis à jour!'
            );

            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($user === null){throw $this->createNotFoundException("ce user n'existe pas");}

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
