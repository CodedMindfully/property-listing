<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';


// Get the restore or delete from deleted page 
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (isset($_GET['id'])) {
	// code...
	$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

	if ($id === false) {
		// code...
		header('Location: delete.php');
		exit();
	}

	$query = "SELECT * FROM properties 
			  WHERE id = :id
			  AND deleted_at IS NOT NULL";
	$stmt = $pdo->prepare($query);
	$stmt->execute([
		':id' => $id
	]);
	$data = $stmt->fetch();

	// If the record doesn't exist or id is fake go back to deleted records
	if (!$data) {
		// code...
		header('Location: deleted.php');
		exit();
	}

}else{
	header('Location: deleted.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// code...
	$id = filter_var($_POST['id'], FILTER_VALIDATE_INT) ?? null;
	// Read action from POST
	$action = $_POST['action'] ?? '';

// If the user clicks on restore
	if ($action === 'restore') {
		// code...
		// if id is present restore the record 
		if ($id) {
			// code...
			$sql = "UPDATE properties
					SET deleted_at = NULL
					WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			$restorRecord = $stmt->execute([
				':id' => $id
			]);
			// If the record is successfully restored
			if ($restorRecord) {
				// code...
				$_SESSION['success'] = "Property successfully restored.";
				header('Location: deleted.php');
				exit();
			}else{
				$_SESSION['error'] = "Could not restore property. Please try again";
				header('Location: deleted.php');
				exit();
			}

		}

	}elseif ($action === 'delete' && $id) {
		// code...
		$sql = "DELETE FROM properties
				WHERE id = :id";
		$stmt = $pdo->prepare($sql);
		$deleteRecord = $stmt->execute([
			':id' => $id
		]);

		// If the query is successful
		if ($deleteRecord) {
			// And image is not empty
			if (!empty($data['image'])) {
				unlink('../assets/images/' . $data['image']);
			}
			// code...
			$_SESSION['success'] = "Property permanently deleted.";
			header('Location: deleted.php');
			exit();
			
		}else{
			$_SESSION['error'] = "Could not delete property. Please try again.";
			header('Location: deleted.php');
			exit();
			
		}
	}
}



$isAdmin = true;
// Display page title by the selected action
// restore or delete.
// I did this because I didn't want to create individual pages for each action
// I'm including my class attribute in these blocks to target delete and restore btn styling
if ($action === 'restore') {
	// code...
	$pageTitle = 'Confirm Restoration';
	$message = "Are you sure you want to restore this property?";
	$btnClass = "restoreBtn";
}else{
	$pageTitle = 'Confirm Permanent Deletion';
	$message = "Are you sure you want to permanently delete this property?";
	$btnClass = "deleteBtn";
}

require_once '../includes/header.php';

?>



<section class="content">
	<div class="displayRecord">
		<p><?php echo $message; ?> </p>
		<div>
			<?php echo displayProperties($data); ?>
		</div>
	</div>
	<div class="form">
		<form method="post">
			<!-- Save the id in the value so db knows which record to restore or delete -->
			<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
			<input type="hidden" name="action" value="<?php echo $action; ?>">
			<a href="deleted.php">Cancel</a>
			<!-- Use a dynamic input label. Input value is dynamic and it changes based on the action the user wishes to undertake (delete or restore) -->
			<input type="submit" name="submit" value="<?php echo ucfirst($action); ?>" class="<?php echo $btnClass; ?>">
		</form>
	</div>
	
</section>


<?php require_once '../includes/footer.php'; ?>