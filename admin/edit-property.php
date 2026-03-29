<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
// require_once 'add-property.php';


// $id = $_GET['id'];
$propName = '';
$price = '';
$location = '';
$status = '';
$errors = [];
$data = '';
// $oldArray = [];
// $newArray = [];

if (isset($_GET['id'])) {
	// code...
	$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

	// if its not a valid integer
	if ($id === false) {
		// code...
		header('Location: index.php');
		exit();
	}

	// if id is a save number
	$idSql = "SELECT * FROM properties WHERE id = :id";
	$idStmt = $pdo->prepare($idSql);
	$idStmt->execute([
		':id' => $id
	]);
	$data = $idStmt->fetch();

	// if the data does not exist in the db redirect
	if (!$data) {
		header('Location: index.php');
		exit();
	}
}else{
	// if no id is provided redirect
	header('Location: index.php');
}


// Update current property
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// code...
	$propName = cleanInputs($_POST['propName']);
	// Strip everything except digits and + -
	$cleanPrice = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
	$location = cleanInputs($_POST['location']);
	$status = cleanInputs($_POST['status']);
	// $imageResult = uploadImage('image');

	if(empty($propName) || empty($location) || empty($status)){
		$errors [] = 'All fields are required.';
	}

	// Check if price is a valid integer
	if (filter_var($cleanPrice, FILTER_VALIDATE_INT) === false) {
		// code...
		$errors [] = 'Price must be a valid number';
	}else{
		$price = $cleanPrice;
	}

	// If there are no errors
	if (empty($errors)) {
		// code...
		// Handle image fallback. This variable 
		//handles the event where user decides to change current image
		$imageResult = uploadImage('image');
		// check if file upload failed 
		// If there is an error in this process retain the current image
		if (strpos($imageResult, 'Error') !== false) {
			// code...
			// Holds current image in the db
			$finalImage = $data['image'];
		}else{
			// If there is no error swap the current image in the db with the new image
			$finalImage = $imageResult;
		}

		// compare current db data to what user is sending
		$oldArray = [
				'name' => $data['name'], 
				// price is int but cast it to string for now
				//for accurate comparison
				'price' => (string)$data['price'], 
				'location' => $data['location'],
				'status' => $data['status'],
				'image' => $data['image']
		];
		$newArray = [
				'name' => $propName,
				'price' => (string)$price,
				'location' => $location,
				'status' => $status,
				// Use the dynamic variable $finalImage for image
				'image' => $finalImage
		];

		if ($oldArray === $newArray) {
				// code...
			$errors [] = 'No changes were made to the property.';
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
				':name' => $propName,
				':price' => $price,
				':location' => $location,
				':status' => $status,
				':image' => $finalImage,
				':id' => $id
			]);

		if ($result) {
			// code...
			// Delete the old image from the image folder if a new one is uploaded
			if ($finalImage !== $data['image']) {
				// code...
				unlink('../assets/images/' . $data['image']);
			}

			$_SESSION['success'] = 'Property updated successfully.';
			// Redirect back to the same page so the user gets a chance
			// to visually see the updated data.
			header('Location: edit-property.php?id=' . $id);
			exit();
		}else{
			$errors [] = 'Database Error: Could not update property.';
		}

	}


	}

}



?>

<section class="content">
	<div class="backBtn">
		<a href="index.php">Go back to Dashboard</a>
	</div>
	<?php 
		// var_dump($oldArray);
		// 	var_dump($newArray);

	?>
	<div class="form">
		<?php if (!empty($errors)) : ?> 
			<div class="displayErrors">
				<ul style="margin: 0;">
					<?php foreach ($errors as $error) :?>
						<li><?php echo htmlspecialchars($error); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<div class="successMsg">
			<?php if (isset($_SESSION['success'])):?>
				<p style="color: green;">
				 	<?php echo $_SESSION['success']; unset($_SESSION['success']);  ?>	
				</p>
			<?php endif;?>
		</div>
		<form accept="" method="post" enctype="multipart/form-data">
			<div class="propForm">
				<label for="name">Name</label>
				<input type="text" name="propName" value="<?php echo $data['name']; ?>">
			</div>
			<div class="propForm">
				<label for="price">Price</label>
				<input type="number" name="price" value="<?php echo $data['price']; ?>">
			</div>
			<div class="propForm">
				<label for="location">Location</label>
				<input type="text" name="location" value="<?php echo $data['location']; ?>">
			</div>
			<div class="propForm">
				<p>This property is current <b><?php echo $data['status']; ?></b> </p>
				<label for="status">Change Property Status</label>
					<select name="status">
						<!-- Print the current status by default -->
						<option value="<?php echo $data['status']; ?>" selected>Current: <?php echo $data['status']; ?></option>
						<!-- Show Available if the current status is not set as Available  -->
						<?php if ($data['status'] !== 'Available') :?> 
						<option value="Available" <?php echo $status === "Available" ? 'selected' : ''; ?>>Available</option>
						<?php endif; ?>
					<!-- Display sold if the current status is not set as Sold -->
					<?php if ($data['status'] !== 'Sold') : ?>
						<option value="Sold" <?php echo $status === 'Sold' ? 'selected' : ''; ?>>Sold</option>
					<?php endif; ?>
					</select>
			</div>
			<div class="propForm">
				<div class="currentImage">
					<p>Current Image</p>
					<img src="../assets/images/<?php echo $data['image']; ?>" width="50" height="50">
				</div>
			</div>
			<div class="propForm">
				<label for="image">Change Image</label>
				<input type="file" name="image">
			</div>
			<div class="propForm">
				<input type="submit" name="submit" value="Update Property">
			</div>
		</form>
	</div>
</section>