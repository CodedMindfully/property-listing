<?php
// Home page listing all properties
require_once 'includes/db.php';
require_once 'includes/functions.php';

//fetch all properties from database
$stmt = $pdo->query("SELECT * FROM properties");
$properties = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo SITE_NAME; ?></title>
</head>
<body>
	<nav>
		<div class="navbar">
			<a href="search.php">Search Properties</a>
		</div>
	</nav>
	<h1><?php echo SITE_NAME; ?></h1>
	<p>Find your perfect property</p>


	<hr>

	<?php if(empty($properties)):?>
		<p>No properties found.</p>
	<?php else: ?>
		<?php foreach($properties as $property): ?>
			<div style="border: 1px solid #ccc; padding:15px; margin-bottom:25px;">
				<h2><?php echo $property['name']; ?></h2>
				<p><strong>Price:</strong> £<?php echo number_format($property['price']);?></p>
				<p><strong>Location:</strong> <?php echo $property['location']; ?></p>
				<p><strong>Status:</strong> <?php echo $property['status']; ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>




</body>

</html>