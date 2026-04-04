<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';


if (isset($_GET['id'])) {
	// code...
	$id = $_GET['id'];
	// validate its a number
	$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

	// If its not a valid integer return to dashboard
	if ($id === false) {
		// code...
		header('Location: index.php');
		exit();
	}

	// Get the logged in user's ID from the session 
	// I'm doing this to make sure admin can't delete each other's property
	//Current logged in users can only delete their own property
	// $loggedInUser = $_SESSION['admin_id'];
	$query = "SELECT * FROM properties 
			WHERE id = :id
			AND deleted_at IS NULL";
	$stmt = $pdo->prepare($query);
	$stmt->execute([
		':id' => $id,
		// ':admin_id' => $loggedInUser
	]);
	$data = $stmt->fetch();

	// If the record doesn't exist or if the id is fake
	if (!$data) {
		// code...
		header('Location: index.php');
		exit();
	}


}else{
	// if id is missing
	header('Location: index.php');
	exit();
}


// Handle the post requests 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// code...
	$cancelBtn = $_POST['cancel'] ?? null;
	$deleteBtn = $_POST['delete'] ?? null;

	if (isset($cancelBtn)) {
		header('Location: index.php');
		exit();
	}

// if user decides to proceed with deletion
	if (isset($deleteBtn)) {
		// code...
		// check the id of the property
		// if id is provide id = the id provided else id is null
		$id = filter_var($_POST['id'], FILTER_VALIDATE_INT) ?? null;
		// If id is present
		if ($id) {
			// code...
			$sql = "UPDATE properties SET deleted_at = NOW() WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			$softDelete = $stmt -> execute([
				':id' => $id
			]);
			// If soft delete is successful
			if($softDelete){
			// code...
			$_SESSION['success'] = "The property has been successfully deleted.";
			header('Location: index.php');
			exit();
			}
		}


	}
}



$isAdmin = true;
$pageTitle = "Delete Property";
require_once '../includes/header.php';
?>

<section class="content">
	<div class="displayRecord">
		<p>Are you sure you want to delete this property?</p>
		<?php echo displayProperties($data); ?>
	</div>
	<div class="form">
		<form action="" method="post">
			<div class="propForm">
				<!-- add the id to the input value so db knows what record to delete -->
				<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
			</div>
				
			<div class="propForm">
				<input type="submit" name="cancel" value="Cancel">
				<input type="submit" name="delete" value="Delete" style="background-color: red; color: whitesmoke;">
			</div>
		</form>
	</div>
	
</section>











<?php require_once '../includes/footer.php'; ?>