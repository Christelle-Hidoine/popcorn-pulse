<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CastingRepository::class)
 */
class Casting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="castings")
     */
    private $movies;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="castings")
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
