<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"genre_browse", "movie_read", "movie_browse", "review_browse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Le titre du film est obligatoire"
     * )
     * 
     * @Groups({"genre_browse", "movie_read", "movie_browse", "review_browse"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message="La durée du film est obligatoire"
     * )
     * @Assert\Positive(
     *      message="Cette valeur ne peut pas être négative."
     * )
     * @Groups({"movie_read", "movie_browse"})
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un avis"
     * )
     * @Groups({"movie_read", "movie_browse"})
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message="Remplir le champs Résumé"
     * )
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "Votre résumé doit contenir un minimum de {{ limit }} caractères. Soyez plus précis svp."
     * )
     * @Groups({"genre_browse", "movie_browse"})
     * 
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message="Remplir le champs Synopsis"
     * )
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Votre synopsis doit contenir un minimum de {{ limit }} caractères. Soyez plus précis svp."
     * )
     * @Groups({"movie_read"})
     */
    private $synopsis;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(
     *      message="Merci d'indiquer une date"
     * )
     * 
     * @Groups({"movie_read"})
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un pays"
     * )
     * @Groups({"movie_read"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url
     * @Assert\NotBlank(
     *      message="Merci de remplir le champs Poster"
     * )
     * @Groups({"movie_read", "movie_browse"})
     */
    private $poster;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un type"
     * )
     * ? https://symfony.com/doc/5.4/reference/constraints/Count.html
     * 
     * @Groups({"movie_read", "movie_browse"})
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     * @Assert\NotBlank(
     *      message="Merci de sélectionner un ou plusieurs genres"
     * )
     *
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movies")
     * 
     * @Groups({"movie_read"})
     */
    private $castings;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="movies")
     * 
     * @Groups({"movie_read"})
     */
    private $seasons;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->seasons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Casting>
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovies($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            // set the owning side to null (unless already changed)
            if ($casting->getMovies() === $this) {
                $casting->setMovies(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setMovies($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getMovies() === $this) {
                $season->setMovies(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     * mettre à jour la date de modification de l'entité Movie dès qu'elle est modifiée côté back
     */
    public function preUpdateCallback()
    {
        $this->updatedAt = new \DateTime("now");

        return $this;
    }

}
