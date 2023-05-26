<?php

namespace App\Controller\Front;

use App\Entity\Review;
use App\Form\Front\ReviewType;
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
     * 
     * @Route("/review/add/{id}", name="add_review", requirements={"id"="\d+"})
     */
    public function index($id, Request $request, ReviewRepository $reviewRepository, MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $reviewForForm = new Review();
        $movie = $movieRepository->find($id);
    
        if ($movie === null){ throw $this->createNotFoundException("Ce film n'existe pas, essaie encore 😜");}

        $form = $this->createForm(
            ReviewType::class,
            $reviewForForm);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {         
            $reviewForForm->setMovie($movie);

            $entityManagerInterface->persist($reviewForForm);
            $entityManagerInterface->flush();

            $reviewByMovie = $reviewRepository->findBy(["movie" => $movie], ["watchedAt" => "DESC"]);
            $rating = [];
            foreach ($reviewByMovie as $review) {
                $rating[] = $review->getRating();
            }
            $sum = array_sum($rating);
            $count = count($rating);
            if ($count !== 0) {
                $average = round($sum/$count, 1);
            } else {
                $average = 0;
            }
            $movie->setRating($average);
            $entityManagerInterface->flush();    

            return $this->redirectToRoute("app_front_movie_show", ["id" => $movie->getId()]);
        }    

        return $this->renderForm('front/review/index.html.twig', [
            'form' => $form,
            'movie' => $movie
        ]);
    }
}
