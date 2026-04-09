<?php
class House extends Property{

	protected int $bedrooms;
	protected bool $has_garage;

	public function __construct(array $row){
		parent::__construct($row);
		$this->bedrooms = (int) $row['bedrooms'];
		$this->has_garage = (bool) ($row['has_garage'] ?? false);
	}

	public function getSummary(): string{
		$garage = $this->has_garage ? "With garage" : "No garage";
		return "{$this->bedrooms} bed house in {$this->location} ({$garage})";
	}
}







?>