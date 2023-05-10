<?php

namespace App\Controller\Front;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/add/{id}", name="add_review", requirements={"id"="\d+"})
     */
    public function index($id, Request $request, ReviewRepository $reviewRepository, MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $reviewForForm = new Review();
        $movie = $movieRepository->find($id);

        // ! si le film n'existe pas : 404
        if ($movie === null){ throw $this->createNotFoundException("Ce film n'existe pas, essaie encore 😜");}
        

        // création du formulaire à partir de notre instance
        $form = $this->createForm(
            // le nom de la classe de formulaire
            ReviewType::class,
            // l'objet associé
            $reviewForForm);
            
        // dump($movie);    

        // TODO : traitement du formulaire
        // 1. on fournit la requete au formulaire pour qu'il aille lui même chercher les infos dedans
        $form->handleRequest($request);

            // on regarde si le formulaire a été soumis
        // on demande à valider les données
        // ! la validation des données n'est pas activé/utilisable par défaut
        if ($form->isSubmitted() && $form->isValid())
        {

            // TODO : Ajouter le movie correspondant à la critique
            $reviewForForm->setMovie($movie);

            // TODO : faire notre insertion en BDD           
            // persist + flush
            $entityManagerInterface->persist($reviewForForm);
            $entityManagerInterface->flush();
            // 2ème possibilité : Repository de la bonne entité : ReviewRepository
            // $reviewRepository->add($newReview, true);

            // TODO : recalculer le rating avec l'ajout 
            $ratingReview = $reviewForForm->getRating();
            $movieReview = $movie->getRating();
            $newRating = $movieRepository->findRatingByMovie($id);
            // dd($ratingReview, $movieReview, $newRating);

            return $this->redirectToRoute("app_front_movie_show", ["id" => $movie->getId()]);
        }    

        $session = $request->getSession();
        $themeSession = $session->get('theme', []);

        return $this->renderForm('front/review/index.html.twig', [
            'theme' => $themeSession,
            'form' => $form,
            'movie' => $movie
        ]);
    }
}
