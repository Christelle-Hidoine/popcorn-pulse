<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
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
 * @Route("/api/genres", name="app_api_genres_")
 */
class GenreController extends CoreApiController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(GenreRepository $genreRepository): JsonResponse
    {
        $allGenres = $genreRepository->findAll();

        return $this->json200($allGenres, ["genre_browse"]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, GenreRepository $genreRepository): JsonResponse
    {
        // TODO : lister tout les genres
        $genre = $genreRepository->find($id);
        if ($genre === null){
            return $this->json(
                [
                    "message" => "Ce genre n'existe pas"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json200($genre, ["genre_read","movie_browse"]);
        
    }

    /**
     * Ajoute un genre
     * 
     * @Route("", name="add", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function add(Request $request, SerializerInterface $serializerInterface, GenreRepository $genreRepository, ValidatorInterface $validatorInterface)
    {
        try {
            /** @var Genre $newGenre */
            $newGenre = $serializerInterface->deserialize(
                $request->getContent(),
                Genre::class,
                'json',
            );
        } catch (Exception $exception) {
            return $this->json("JSON Invalide: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $errors = $validatorInterface->validate($newGenre);
        if (count($errors)> 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $genreRepository->add($newGenre, true);

        return $this->json201($newGenre, ["genre_read","movie_browse"]);
    }

    /**
     * edite un genre
     * 
     * @Route("/{id}", name="edit", requirements={"id"="\d+"}, methods={"PUT", "PATCH"})
     *
     * @param [int] $id
     * @param GenreRepository $genreRepository
     * @param SerializerInterface $serializerInterface
     * @param Request $request
     * 
     */
    public function edit(
        $id, 
        GenreRepository $genreRepository, 
        SerializerInterface $serializerInterface, 
        Request $request,
        ValidatorInterface $validatorInterface)
    {
        // TODO : mettre à jour un genre

        $genre = $genreRepository->find($id);
        try {
            $serializerInterface->deserialize(
                $request->getContent(),
                Genre::class,
                'json',
                // ? https://symfony.com/doc/5.4/components/serializer.html#deserializing-in-an-existing-object
                [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]
            );
        } catch (Exception $exception) {
            return $this->json("JSON Invalide: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $errors = $validatorInterface->validate($genre);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // * comme on a demandé la màj d'un objet, pas besoin de récupérer la déserialization
        $genreRepository->add($genre, true);

        return $this->json200($genre, ["genre_read", "movie_browse"]);

    }

    /**
     * supprime un genre
     * 
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     *
     * @param [int] $id
     * @param GenreRepository $genreRepository
     * 
     */
    public function delete($id, GenreRepository $genreRepository)
    {
        // TODO : supprimer un genre

        $genre = $genreRepository->find($id);
        $genreRepository->remove($genre, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);

    }    
}
