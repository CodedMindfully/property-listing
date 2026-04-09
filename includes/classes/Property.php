<?php 

Class Property {

	// Private properties
	protected int $id;
	protected string $title;
	protected int $price;
	protected string $location;
	protected string $status;
	// ?string $image; = this can be string or null
	// ? prefix on a type is called a nullable type
	protected ?string $image;
	protected string $created_at;
	protected ?string $listed_by;

// Constructor to populate the object
	public function __construct (array $row){
		$this->id = (int) $row['id'];
		$this->title =  $row['title'];
		$this->price = (int) $row['price'];
		$this->location = $row['location'];
		$this->status = $row['status'];
		$this->image = $row['image'];
		$this->created_at = $row['created_at'];
		$this->listed_by = $row['listed_by'] ?? null;
	}

	// 
	public function getFormattedPrice(): string{
		// if price a while number? if yes display a whole number
		//else display price with two numbers after the decimal 
		$decimals = ($this->price % 1 === 0) ? 0 : 2;
		// Price of house will always be an integer. 
		//I should use this instead. The 0 tells php never to show anything after the decimal point.
		return "£" . number_format($this->price, 0);
		

	}

	public function getSummary(): string {
		return "{$this->title} - {$this->location}";
	}

	public function getListedDate(): string{
		return date('F j, Y', strtotime($this->created_at));
	}

	// returns true if the house is cheaper than or equal to the budge
	//returns false if the house is too expensive
	public function isAffordable(int $budget): bool {
		return $this->price <= $budget;
	}

	// I could use this isAffordable method instead of the Yes or No 
	// This object does a luxury maths giving people the opportunity to go 10 over their budget
	// public function isAffordable(int $budget){
	// 	$flexibleBudge = $budget * 1.10;
	// 	// Add 10% by multiplying the budge by 1.10
	// 	return $this->price <= $flexibleBudge;
	// }

	// Is the property available?
	public function isAvailable(): bool{
		// Convert status to lowercase and return true if status matches available and false for everything else
		return strtolower($this->status) === 'available';
	}

	// Stickers to put on the property images
	public function getBadge(){
		// If isAvailable() is true return "for sale" if false, its "sold"
		if ($this->isAvailable() === true) {
			// code...
			return "Available";
		}else{
			return "Sold";
		}
	}

	// Getter methods 
	public function getId(): int{
		return $this->id;
	}

	public function getTitle(): string{
		return $this->title;
	}

	public function getLocation(): string{
		return $this->location;
	}

	// ?string = Can be a string or Null
	public function getImage(): ?string{
		return $this->image;
	}

	public function getStatus(): string{
		return $this->getBadge();
	}

	public function getListedBy(): string{
		// return the name of the agent that listed it if known
		//else return "Unknown"
		return $this->listed_by ?? 'Unknown';
	}

	// Get price for display price on edit page
	public function getPrice(): int{
		return $this->price;
	}

}




?>