<?php
require_once '../includes/auth.php';
//temporary debug
// var_dump($_SESSION);
// die();
require_once '../includes/db.php';
require_once '../includes/functions.php';

$sql = "SELECT * FROM properties WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();



$isAdmin = true;



$pageTitle = "Delete Properties";
require_once '../includes/header.php';


?>

<section class="content">
	<div class="propDisplay">
		<?php showFlashMessages(); ?>
		<p><?php echo "Number of deleted properties " . count($results); ?></p>
	<?php if (empty($results)) :?>
		<p>No deleted properties found.</p>
	<?php else: ?>
		<table style="width: 100%">
			<tr>
				<th>Name</th>
				<th>Price</th>
				<th>Location</th>
				<th>Status</th>
				<th>Image</th>
				<th>Action</th>
			</tr>
			<?php foreach ($results as $property) : ?>
			<tr>
				<td><?php echo htmlspecialchars($property['name'])?></td>
				<td><?php echo "£" . htmlspecialchars($property['price'])?></td>
				<td><?php echo htmlspecialchars($property['location'])?></td>
				<td><?php echo htmlspecialchars($property['status'])?></td>
				<td><img src="../assets/images/<?php echo htmlspecialchars($property['image']); ?>" 
					height="50" 
					width="50"></td>
				<td>
					<!-- action=restore tells the next page what I want to do. This link tells it I want to confirm a restore action. I'm doing this because I don't want to create two file (delete confirmation and restore confirmation) -->
					<a href="confirmation.php?action=restore&id=<?php echo $property['id']; ?>">Restore</a>
					<a href="confirmation.php?action=delete&id=<?php echo $property['id']; ?>">Delete</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
</section>

<?php require_once '../includes/footer.php'; ?>