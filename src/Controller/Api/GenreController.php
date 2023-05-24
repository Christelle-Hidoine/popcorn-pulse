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
        // TODO : lister tout les genres
        // BDD, Genre : GenreRepository
        $allGenres = $genreRepository->findAll();

        // le serializer est caché derrière la méthode json()
        // on lui donne les objets à serializer en JSON, ainsi qu'un contexte
        return $this->json200($allGenres, ["genre_browse"]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, GenreRepository $genreRepository): JsonResponse
    {
        // TODO : lister tout les genres
        // BDD, Genre : GenreRepository
        $genre = $genreRepository->find($id);
        // gestion 404
        if ($genre === null){
            // ! on est dans une API donc pas de HTML
            // throw $this->createNotFoundException();
            return $this->json(
                // on pense UX : on fournit un message
                [
                    "message" => "Ce genre n'existe pas"
                ],
                // le code de status : 404
                Response::HTTP_NOT_FOUND
                // on a pas besoin de preciser les autres arguments
            );
        }

        // le serializer est caché derrière la méthode json()
        // on lui donne les objets à serializer en JSON, ainsi qu'un contexte
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
        // TODO : créer un genre
        // TODO : récupérer les infos fournies par notre utilisateur
        // comme dans les formulaires on va chercher les infos dans Request
        // Request = on veut le contenu

        $jsonContent = $request->getContent();
        // dd($jsonContent);
        /* 
        {
            "name": "Radium"
        }
        */
        // TODO : tranformer / deserialiser le json en objet
        // j'utilise le service SerializerInterface pour ça
        try {
            /** @var Genre $newGenre */
            $newGenre = $serializerInterface->deserialize(
                // les données à transformer/deserializer
                $jsonContent,
                // vers quel type d'objet je veux deserialize
                Genre::class,
                // quel est le format du contenu : json
                'json',
                // le paramètre de contexte nous serivra pour les update
            );
        } catch (Exception $exception) {
            return $this->json("JSON Invalide: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $errors = $validatorInterface->validate($newGenre);
        if (count($errors)> 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // * j'ai un objet Genre, prêt à être envoyé en BDD
        // BDD, Genre, GenreRepository
        $genreRepository->add($newGenre, true);

        // TODO : un peu d'UX : on renvoit le bon code de statut : 201
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
        // 3. Deserializer et mettre à jour l'existant
        // 3. désérialiser tout en mettant à jour l'objet existant
        try {
            $serializerInterface->deserialize(
                // les données
                $request->getContent(),
                // le type d'objet
                Genre::class,
                // le format de donnée
                'json',
                // ? https://symfony.com/doc/5.4/components/serializer.html#deserializing-in-an-existing-object
                // en contexte on précise que l'on veux POPULATE / REMPLIR un objet existant
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
        // 4. flush
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
