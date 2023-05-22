<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *      message="Le pseudonyme est obligatoire"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message = "L'email '{{ value }}' n'est pas valide."
     * )
     * @Assert\NotBlank(
     *      message="L'email est obligatoire"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message="On attend une critique à la base..."
     * )
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Votre critique doit contenir un minimum de {{ limit }} caractères. Soyez plus précis svp."
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un avis"
     * )
     * 
     */
    private $rating;

    /**
     * @ORM\Column(type="json")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner au minimum une réaction"
     * )
     */
    private $reactions = [];

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank(
     *      message="Merci d'indiquer une date"
     * )
     * @Assert\Type("\DateTimeImmutable")
     *
     */
    private $watchedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class)
     * 
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReactions(): ?array
    {
        return $this->reactions;
    }

    public function setReactions(array $reactions): self
    {
        $this->reactions = $reactions;

        return $this;
    }

    public function getWatchedAt(): ?\DateTimeImmutable
    {
        return $this->watchedAt;
    }

    public function setWatchedAt(\DateTimeImmutable $watchedAt): self
    {
        $this->watchedAt = $watchedAt;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}