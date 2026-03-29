<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';


$propName = '';
$price = '';
$location = '';
$status = '';
// $image
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// code...
	$propName = cleanInputs($_POST['propName']);
	// strip everything except digits and + -
	$cleanPrice = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
	// $price = cleanInput($_POST['price']);
	$location = cleanInputs($_POST['location']);
	$status = cleanInputs($_POST['status']);
	// Processing image
	$imageResult = uploadImage('image');

	// These fields should not be empty
	if (empty($propName) || empty($location) || empty($status)) {
		// code...
		$errors [] = 'All fields are required';
	}

	// Check if price is a valid integer
	if ($cleanPrice != '' && filter_var($cleanPrice, FILTER_VALIDATE_INT) === false) {
		// code...
		$errors [] = 'Price must be a valid number.';
	}else {
		// code...
		$price = $cleanPrice;
	}

	if (empty($errors)) {
		// code...
		// Check if image upload failed
		if (strpos($imageResult, 'Error') !== false) {
			// code...
			// Add image error to the list of errors
			$errors[] = $imageResult;
		}else{
		// code...
		// Upload everything to db
		$query = "INSERT INTO properties (name, price, location, status, image)
					VALUES(:name, :price, :location, :status, :image)";
		$stmt = $pdo->prepare($query);
		$success = $stmt->execute([
			':name' => $propName,
			':price' => $price,
			':location' => $location,
			':status' => $status,
			':image' => $imageResult
		]);

	if ($success) {
		// code...
		// echo 'successfully uploaded';
		// Display a success message
		$_SESSION['success'] = 'Property added successfully';
		// Redirect to the dashboard
		header('Location: index.php');
		exit();

	}else{
		$errors[] = 'Database error: Could not save property.';
	}
}
}

}






$pageTitle = "Add Property";

require_once '../includes/header.php';

?>

<section class="content">
	<div class="fileUpload">
		<form method="POST" enctype="multipart/form-data" action="">
			<?php if (!empty($errors)) :?> 
				<div class="displayErrors">
					<ul style="margin: 0;">
						<?php foreach($errors as $error) : ?>
							<li><?php echo htmlspecialchars($error); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<div class="propForm">
				<label for="name">
					Property Name
				</label>
				<input type="text" name="propName" value="<?php echo $propName; ?>">
			</div>
			<div class="propForm">
				<label for="price">Price</label>
				<!-- Add step='1' enforce the browser to only accept whole number which matches FILTER_VALIDATE_INT -->
				<input type="number" name="price" step="1" value="<?php echo $price; ?>">
			</div>
			<div class="propForm">
				<label for="location">Location</label>
				<input type="text" name="location" value="<?php echo $location; ?>">
			</div>
			<div class="propForm">
				<label for="status">
					<select name="status">
						<option value="">Any</option>
						<option value="Available" <?php echo $status === 'Available' ? 'selected' : ''; ?>>Available</option>
						<option value="Sold" <?php echo $status === 'Sold' ? 'selected' : ''; ?>>Sold</option>
					</select>
				</label>
			</div>
			<div class="selectUpload propForm">
				<label for="fileUpload">
					Select image to upload
				</label>
				<input type="file" name="image" id="UploadUploads">
			</div>
			<div class="selectUpload">
				<input type="submit" name="submit" value="Upload Property">
			</div>
			

		</form>
	</div>
</section>