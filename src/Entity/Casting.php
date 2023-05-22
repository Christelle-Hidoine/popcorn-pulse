<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CastingRepository::class)
 */
class Casting
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
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\NotBlank(
     *      message="Le nom du personnage est obligatoire"
     * )
     * 
     * @Groups({"movie_read"})
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(
     *      message="L'ordre d'importance des acteur est obligatoire"
     * )
     * @Assert\Positive(
     *      message="Cette valeur ne peut pas être négative."
     * )
     * 
     * @Groups({"movie_read"})
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="castings")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un film ou une série"
     * )
     */
    private $movies;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="castings")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un acteur ou une actrice"
     * )
     * @Groups({"movie_read"})
     */
    private $persons;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(?int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

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

    public function getPersons(): ?Person
    {
        return $this->persons;
    }

    public function setPersons(?Person $persons): self
    {
        $this->persons = $persons;

        return $this;
    }
}
