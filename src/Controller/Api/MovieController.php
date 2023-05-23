<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/movies", name="app_api_movies_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(MovieRepository $movieRepository): JsonResponse
    {
        // TODO : lister tout les movies
        $allMovies = $movieRepository->findAll();

        // le serializer est caché derrière la méthode json()
        // on lui donne les objets à serializer en JSON, ainsi qu'un contexte
        return $this->json(
            // les données
            $allMovies, 
            // le code de retour : 200 par défaut
            200,
            // les entêtes HTTP, on ne s'en sert pas : []
            [],
            // le contexte de serialisation : les groupes
            [
                "groups" => 
                [
                    "movie_browse",
                ]
            ]
        );
        
    }
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id,MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->find($id);

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
        return $this->json($movie, 200, [], 
            [
                "groups" => 
                [
                    "movie_read"
                ]
            ]);
    }   

    /**
     * ajout de film
     *
     * @Route("", name="add", methods={"POST"})
     * 
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param MovieRepository $movieRepository
     * 
     * @ IsGranted("ROLE_ADMIN")
     */
    public function add(
        Request $request, 
        SerializerInterface $serializer, 
        MovieRepository $movieRepository,
        ValidatorInterface $validatorInterface)
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        // Désérialiser (convertir) le JSON en entité Doctrine Movie
        try { // on tente de désérialiser
            $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        } catch (EntityNotFoundException $entityNotFoundException){
            // Si on n'y arrive pas, on passe ici
            // dd($exception);
            // code 400 ou 422
            return $this->json("Denormalisation : " . $entityNotFoundException->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return $this->json("JSON Invalide : " .$exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        
        // on valide les données de notre entité
        // ? https://symfony.com/doc/5.4/validation.html#using-the-validator-service
        $errors = $validatorInterface->validate($movie);
        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // TODO Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // On sauvegarde l'entité
        $movieRepository->add($movie, true);

        return $this->json($movie, Response::HTTP_CREATED, [], ["groups"=>["movie_read"]]);
    }

    /**
     * supprime un movie
     * 
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     *
     * @param [int] $id
     * @param MovieRepository $movieRepository
     * 
     */
    public function delete($id, MovieRepository $movieRepository)
    {
        // TODO : supprimer un movie

        $movie = $movieRepository->find($id);
        $movieRepository->remove($movie, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);

    }

}
