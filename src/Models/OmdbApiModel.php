<?php

namespace App\Models;

class OmdbApiModel
{
	public const REPONSE_FALSE = "False";
	public const RESPONSE_TRUE = "True";
	public const DEFAULT_POSTER = "https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg";
    public const DEFAULT_PLOT = "Texte par défaut";
    public const DEFAULT_COUNTRY = "United States";

	private string $title;
	private string $year;
	private string $rated;
	private string $released;
	private string $runtime;
	private string $genre;
	private string $director;
	private string $writer;
	private string $actors;
	private string $plot;
	private string $language;
	private string $country;
	private string $awards;
	private string $poster;
	/** @var OmdbApiRatingModel[] */
	private array $ratings;
	private string $metascore;
	private string $imdbRating;
	private string $imdbVotes;
	private string $imdbId;
	private string $type;
	private string $dvd;
	private string $boxOffice;
	private string $production;
	private string $website;
	private string $response;
	private $error;
	
	public function getError(): string
	{
		return $this->error;
	}

	public function getPoster(): string
	{
		if ($this->response === OmdbApiModel::REPONSE_FALSE)
        {
            return OmdbApiModel::DEFAULT_POSTER;
        }
        return $this->poster;
	}


	public function getTitle(): string
	{
		return $this->title;
	}

	public function getYear(): string
	{
		return $this->year;
	}

	public function getRated(): string
	{
		return $this->rated;
	}

	public function getReleased(): string
	{
		return $this->released;
	}

	public function getRuntime(): string
	{
		return $this->runtime;
	}

	public function getGenre(): string
	{
		return $this->genre;
	}

	public function getDirector(): string
	{
		return $this->director;
	}

	public function getWriter(): string
	{
		return $this->writer;
	}

	public function getActors(): string
	{
		return $this->actors;
	}

	public function getPlot(): string
	{
        if ($this->response === OmdbApiModel::REPONSE_FALSE)
        {
            return OmdbApiModel::DEFAULT_PLOT;
        }
		return $this->plot;
	}

	public function getLanguage(): string
	{
		return $this->language;
	}

	public function getCountry(): string
	{
        if ($this->response === OmdbApiModel::REPONSE_FALSE)
        {
            return OmdbApiModel::DEFAULT_COUNTRY;
        }
		return $this->country;
	}

	public function getAwards(): string
	{
		return $this->awards;
	}

	
	/**
	 * @return OmdbApiRatingModel[]
	 */
	public function getRatings(): array
	{
		return $this->ratings;
	}

	public function getMetascore(): string
	{
		return $this->metascore;
	}

	public function getImdbRating(): string
	{
		return $this->imdbRating;
	}

	public function getImdbVotes(): string
	{
		return $this->imdbVotes;
	}

	public function getImdbId(): string
	{
		return $this->imdbId;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getDvd(): string
	{
		return $this->dvd;
	}

	public function getBoxOffice(): string
	{
		return $this->boxOffice;
	}

	public function getProduction(): string
	{
		return $this->production;
	}

	public function getWebsite(): string
	{
		return $this->website;
	}

	public function getResponse(): string
	{
		return $this->response;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function setYear(string $year): void
	{
		$this->year = $year;
	}

	public function setRated(string $rated): void
	{
		$this->rated = $rated;
	}

	public function setReleased(string $released): void
	{
		$this->released = $released;
	}

	public function setRuntime(string $runtime): void
	{
		$this->runtime = $runtime;
	}

	public function setGenre(string $genre): void
	{
		$this->genre = $genre;
	}

	public function setDirector(string $director): void
	{
		$this->director = $director;
	}

	public function setWriter(string $writer): void
	{
		$this->writer = $writer;
	}

	public function setActors(string $actors): void
	{
		$this->actors = $actors;
	}

	public function setPlot(string $plot): void
	{
		$this->plot = $plot;
	}

	public function setLanguage(string $language): void
	{
		$this->language = $language;
	}

	public function setCountry(string $country): void
	{
		$this->country = $country;
	}

	public function setAwards(string $awards): void
	{
		$this->awards = $awards;
	}

	public function setPoster(string $poster): void
	{
		$this->poster = $poster;
	}

	/**
	 * @param OmdbApiRatingModel[] $ratings
	 */
	public function setRatings(array $ratings): void
	{
		$this->ratings = $ratings;
	}

	public function setMetascore(string $metascore): void
	{
		$this->metascore = $metascore;
	}

	public function setImdbRating(string $imdbRating): void
	{
		$this->imdbRating = $imdbRating;
	}

	public function setImdbVotes(string $imdbVotes): void
	{
		$this->imdbVotes = $imdbVotes;
	}

	public function setImdbId(string $imdbId): void
	{
		$this->imdbId = $imdbId;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function setDvd(string $dvd): void
	{
		$this->dvd = $dvd;
	}

	public function setBoxOffice(string $boxOffice): void
	{
		$this->boxOffice = $boxOffice;
	}

	public function setProduction(string $production): void
	{
		$this->production = $production;
	}

	public function setWebsite(string $website): void
	{
		$this->website = $website;
	}

	public function setResponse(string $response): void
	{
		$this->response = $response;
	}
}