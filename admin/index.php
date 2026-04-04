<?php
// Admin Dashboard
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

$query = "SELECT * FROM properties WHERE deleted_at IS NULL";
$stmt = $pdo->prepare($query);
$stmt->execute();
$properties = $stmt->fetchAll();



// Declear a variable that calls the admin navigation
$isAdmin = true;
$pageTitle = htmlspecialchars('Dashboard');
require_once '../includes/header.php';

?>

<section class="content">
	<div class="welcome">
		<p><?php echo "Welcome: " . $_SESSION['name']; ?></p>
	</div>
		<!-- Display the success message and distroy the variable -->
		<?php if (isset($_SESSION['success'])): ?>
			<div class="displaySuccess">
				<p style="color: green;"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']);?></p>
			</div>
		<?php  endif; ?>
	<div class="propDisplay">
		<?php if (empty($properties)) :?>
			<p><?php echo 'There are no properties.' ;?></p>
		<?php else :?>
			<table style="width: 100%;">
				<p> Number of total properties <?php echo count($properties); ?></p>
				<?php 
				// Get all the status into a single array
					$statuses = array_column($properties, 'status');
					// Count the occurences of each status
					$counts = array_count_values($statuses);
					// Get the specific count for sold properties and if nothing is found set default to 0
					$soldCount = $counts['Sold'] ?? 0;
					// Null coalescing Get the specific count for 
					//available properties and if nothing is found set default to 0
					$availCount = $counts['Available'] ?? 0;
				?>
				<p>Number of properties available <?php echo $availCount; ?></p>
				<p>Number of properties sold <?php echo $soldCount; ?></p>
				<tr>
					<th>Name</th>
					<th>Price</th>
					<th>Location</th>
					<th>Status</th>
					<th>Image</th>
					<th>Action</th>
				</tr>
				<?php foreach($properties as $property) : ?>
				<tr>
					<td><?php echo htmlspecialchars($property['name']); ?></td>
					<td><?php echo htmlspecialchars($property['price']); ?></td>
					<td><?php echo htmlspecialchars($property['location']); ?></td>
					<td><?php echo htmlspecialchars($property['status']); ?></td>
					<td><img src="../assets/images/<?php echo htmlspecialchars($property['image']); ?>" height="50" width="50"></td>
					<td style="display: flex; gap: 10px;">
						<a href="edit-property.php?id=<?php echo $property['id'] ?>" class="actionBtn">Edit</a>
						<a href="delete-property.php?id=<?php echo $property['id']; ?>" class="actionBtn" id="deleteBtn">Delete</a>
						<a href="/property.php?id=<?php echo $property['id']; ?>" target="_blank">View Listing</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>

		<?php endif;?>

	</div>
</section>
<?php require_once '../includes/footer.php';?>