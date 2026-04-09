<?php
require_once '../includes/auth.php';
//temporary debug
// var_dump($_SESSION);
// die();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/classes/Property.php';

$sql = "SELECT * FROM properties WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll();


// create an empty array to hold the records from db
$properties = [];

// Loop through the records and save them in the new object
foreach ($rows as $row) {
	// code...
	$properties [] = new Property($row);
}

$isAdmin = true;



$pageTitle = "Delete Properties";
require_once '../includes/header.php';


?>

<section class="content">
	<div class="propDisplay">
		<?php showFlashMessages(); ?>
		<p><?php echo "Number of deleted properties " . count($properties); ?></p>
	<?php if (empty($properties)) :?>
		<p>No deleted properties found.</p>
	<?php else: ?>
		<table style="width: 100%">
			<tr>
				<th>Title</th>
				<th>Price</th>
				<th>Location</th>
				<th>Status</th>
				<th>Image</th>
				<th>Action</th>
			</tr>
			<?php foreach ($properties as $property) : ?>
			<tr>
				<td><?php echo htmlspecialchars($property->getTitle());?></td>
				<td><?php echo "£" . htmlspecialchars($property->getFormattedPrice());?></td>
				<td><?php echo htmlspecialchars($property->getLocation());?></td>
				<td><?php echo htmlspecialchars($property->getStatus());?></td>
				<td><img src="../assets/images/<?php echo htmlspecialchars($property->getImage()); ?>" 
					height="50" 
					width="50"></td>
				<td>
					<!-- action=restore tells the next page what I want to do. This link tells it I want to confirm a restore action. I'm doing this because I don't want to create two file (delete confirmation and restore confirmation) -->
					<a href="confirmation.php?action=restore&id=<?php echo $property->getId(); ?>">Restore</a>
					<a href="confirmation.php?action=delete&id=<?php echo $property->getId(); ?>">Delete</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
</section>

<?php require_once '../includes/footer.php'; ?>