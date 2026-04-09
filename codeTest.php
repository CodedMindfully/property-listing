<?php




// Update current property
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// code...
	$propTitle = cleanInputs($_POST['propTitle']);
	// Strip everything except digits and + -
	$cleanPrice = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
	$location = cleanInputs($_POST['location']);
	$status = cleanInputs($_POST['status']);
	$imageResult = uploadImage('image');

	if(empty($propTitle) || empty($location) || empty($status)){
		$errors [] = 'All fields are required.';
	}

	// Check if price is a valid integer
	if ($cleanPrice != '' && filter_var($cleanPrice, FILTER_VALIDATE_INT)) {
		// code...
		$errors [] = 'Price must be a valid number';
	}else{
		$price = $cleanPrice;
	}

	if (empty($errors)) {
		// code...
		// check if file upload failed 
		if (strpos($imageResult, 'Error') !== false) {
			// code...
			$errors [] = $imageResult;
		}else{
			// Update the entries
			$sql = "UPDATE properties 
					SET name = :name, 
					price = :price, 
					location = :location, 
					status = :status,
					image = :image
					WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			$result = $stmt->execute([
				':name' => $propTitle,
				':price' => $price,
				':location' => $location,
				'status' => $status,
				':image' => $imageResult,
				':id' => $id
			]);

			if ($result) {
				// code...
				$updateMsg = 'The property record has been updated.';
			}else{
				$errors [] = 'Database Error: Could not update property.';
			}
		}
	}

}

?>