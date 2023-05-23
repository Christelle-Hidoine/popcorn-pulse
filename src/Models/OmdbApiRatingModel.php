<?php

namespace App\Models;


class OmdbApiRatingModel
{
	private string $source;
	private string $value;

	public function getSource(): string
	{
		return $this->source;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function setSource(string $source): void
	{
		$this->source = $source;
	}

	public function setValue(string $value): void
	{
		$this->value = $value;
	}
}