<?php

class Apartment extends Property {
	protected int $floor_number;

	public function __construct(array $row){
		parent::__construct($row);
		// Use null coalescing as a fallback
		// Set floor_number to null if it doesn't exist
		$this->floor_number = (int) $row['floor_number'] ?? 0;

	}

	public function getSummary(): string{
		return "{$this->title} - {$this->floor_number} - {$this->location}";
	}

}

?>
