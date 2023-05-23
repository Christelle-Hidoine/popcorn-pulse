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

       // le serializer est caché derrière la méthode json()
       // on lui donne les objets à serializer en JSON, ainsi qu'un contexte
       return $this->json(
           // les données
           $allReviews, 
           // le code de retour : 200 par défaut
           200,
           // les entêtes HTTP, on ne s'en sert pas : []
           [],
           // le contexte de serialisation : les groupes
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
            // ! on est dans une API donc pas de HTML
            // throw $this->createNotFoundException();
            return $this->json(
                // on pense UX : on fournit un message
                [
                    "message" => "Ce film n'existe pas"
                ],
                // le code de status : 404
                Response::HTTP_NOT_FOUND
                // on a pas besoin de preciser les autres arguments
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
        // TODO : créer un review

        // TODO : tranformer / deserialiser le json en objet
        try {
            /** @var Review $newReview */
            $newReview = $serializerInterface->deserialize(
                // les données à transformer/deserialiser
                $request->getContent(),
                // vers quel type d'objet je veux deserialiser
                Review::class,
                // quel est le format du contenu : json
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
            // on fournit l'objet crée
            $newReview,
            // le code 201 pour la création
            Response::HTTP_CREATED,
            // toujour pas d'entête
            [],
            // on oublie pas le contexte car on serialise un objet
            [
                "groups" =>
                [
                    // j'utilise un groupe déjà existant
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
                // les données
                $request->getContent(),
                // le type d'objet
                Review::class,
                // le format de donnée
                'json',
                // ? https://symfony.com/doc/5.4/components/serializer.html#deserializing-in-an-existing-object
                // en contexte on précise que l'on veux POPULATE / REMPLIR un objet existant
                [AbstractNormalizer::OBJECT_TO_POPULATE => $review]
            );
        } catch (Exception $exception) {
            return $this->json("JSON Invalide: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $errors = $validatorInferface->validate($review);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // * comme on a demandé la màj d'un objet, pas besoin de récupérer la déserialization
        // 4. flush
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
        // TODO : supprimer un review

        $review = $reviewRepository->find($id);
        $reviewRepository->remove($review, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);

    }
}
