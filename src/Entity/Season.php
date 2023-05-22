<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"movie_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(
     *      message="Le numéro de la saison est obligatoire"
     * )
     * @Assert\Positive(
     *      message="Cette valeur ne peut pas être négative."
     * )
     * 
     * @Groups({"movie_read"})
     */
    private $number;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(
     *      message="Le nombre d'épisodes est obligatoire"
     * )
     * @Assert\Positive(
     *      message="Cette valeur ne peut pas être négative."
     * )
     * 
     * @Groups({"movie_read"})
     */
    private $nbEpisodes;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="seasons")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner une série"
     * )
     */
    private $movies;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNbEpisodes(): ?int
    {
        return $this->nbEpisodes;
    }

    public function setNbEpisodes(?int $nbEpisodes): self
    {
        $this->nbEpisodes = $nbEpisodes;

        return $this;
    }

    public function getMovies(): ?Movie
    {
        return $this->movies;
    }

    public function setMovies(?Movie $movies): self
    {
        $this->movies = $movies;

        return $this;
    }
}
