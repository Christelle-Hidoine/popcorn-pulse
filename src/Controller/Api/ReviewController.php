<?php

namespace App\Controller\Api;

use App\Entity\Review;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/reviews", name="app_api_reviews_")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(ReviewRepository $reviewRepository): JsonResponse
    {
       // TODO : lister tout les reviews
       $allReviews = $reviewRepository->findAll();

       return $this->json(
           $allReviews, 
           200,
           [],
           [
               "groups" => 
               [
                   "review_browse",
               ]
           ]
       );
    }

    /**
     * @Route("/movies/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, MovieRepository $movieRepository, ReviewRepository $reviewRepository): JsonResponse
    {
        $movie = $movieRepository->find($id);
        $reviewByMovie = $reviewRepository->findBy(["movie" => $movie], ["watchedAt" => "DESC"]);
        if ($movie === null){
            return $this->json(
                [
                    "message" => "Ce film n'existe pas"
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return $this->json($reviewByMovie, 200, [], 
            [
                "groups" => 
                [
                    "review_browse",
                ]
            ]);
    }

    /**
     * ajout de review
     *
     * @Route("",name="add", methods={"POST"})
     * 
     * @return JsonResponse
     */
    public function add(
        Request $request, 
        SerializerInterface $serializerInterface,
        ReviewRepository $reviewRepository,
        ValidatorInterface $validatorInterface)
    {

        try {
            /** @var Review $newReview */
            $newReview = $serializerInterface->deserialize(
                $request->getContent(),
                Review::class,
                'json'
            );
        } catch (Exception $exception){ 
            return $this->json("JSON Invalide: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validatorInterface->validate($newReview);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $reviewRepository->add($newReview, true);

        return $this->json(
            $newReview,
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "movie_read",
                    "review_browse"
                ]
            ]
        );
    }

    /**
     * edite un review
     * 
     * @Route("/{id}", name="edit", requirements={"id"="\d+"}, methods={"PUT", "PATCH"})
     *
     * @param [int] $id
     * @param ReviewRepository $reviewRepository
     * @param SerializerInterface $serializerInterface
     * @param Request $request
     * 
     */
    public function edit(
        $id,
        ReviewRepository $reviewRepository, 
        SerializerInterface $serializerInterface, 
        Request $request,
        ValidatorInterface $validatorInferface)
    {
        // TODO : mettre à jour un review

        $review = $reviewRepository->find($id);
        try {
            $serializerInterface->deserialize(
                $request->getContent(),
                Review::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $review]
            );
        } catch (Exception $exception) {
            return $this->json("JSON Invalide: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $errors = $validatorInferface->validate($review);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $reviewRepository->add($review, true);

        return $this->json($review, Response::HTTP_OK, [], ["groups" => ["movie_read", "review_browse"]]);

    } 

    /**
     * supprime un review
     * 
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     *
     * @param [int] $id
     * @param ReviewRepository $reviewRepository
     * 
     */
    public function delete($id, ReviewRepository $reviewRepository)
    {

        $review = $reviewRepository->find($id);
        $reviewRepository->remove($review, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);

    }
}
