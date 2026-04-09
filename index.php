<?php
require_once 'includes/db.php';
// Home page listing all properties
require_once 'includes/functions.php';
require_once 'includes/classes/Property.php';
require_once 'includes/classes/House.php';
require_once 'includes/classes/Apartment.php';
//fetch all properties from database
// $stmt = $pdo->query("SELECT * FROM properties WHERE deleted_at IS NULL");
// $rows = $stmt->fetchAll();


// Use Aliases for query readability
// Do this to keep things clean
$stmt = $pdo->prepare(
				"SELECT 
					p.id,
					p.title,
					p.price,
					p.location,
					p.status,
					p.image,
					p.created_at,
					a.name AS listed_by
				FROM properties p
				LEFT JOIN admins a ON p.admin_id = a.id
				WHERE p.deleted_at IS NULL
				ORDER BY p.created_at DESC"
);
$stmt->execute();
$rows = $stmt->fetchAll();

// turn each database row into a Property object
$properties = [];

foreach ($rows as $row) {
	// code...
	$properties [] = new Property($row);
}



//HTML output after all logic is complete
require_once 'includes/header.php';
?>

	<h1><?php echo SITE_NAME; ?></h1>
	<p>Find your perfect property</p>


	<hr>
	
	<?php if(empty($properties)):?>
		<p>No properties found.</p>
	<?php else: ?>
		<?php foreach($properties as $property): ?>
				<div style="border: 1px solid #ccc; padding:15px; margin-bottom:25px;">
					<a href="property.php?id=<?php echo $property->getId(); ?>">
						<h2><?php echo htmlspecialchars($property->getTitle()); ?></h2>
						<p><strong>Price:</strong> <?php echo $property->getFormattedPrice();?></p>
						<p><strong>Location:</strong> <?php echo htmlspecialchars($property->getLocation()); ?></p>
						<p><strong>Status:</strong> <?php echo $property->getStatus(); ?></p>
						<p><strong>Image:</strong><img src="assets/images/<?php echo $property->getImage(); ?>" height="50" width="50"></p>
						<p><strong>Listing Date:</strong> <?php echo $property->getListedDate(); ?></p>
						<p><strong>Listed By:</strong> <?php echo htmlspecialchars($property->getListedBy()); ?></p>

						<?php if ($property->isAvailable()) :?> 
							<span style="color: green;">Available</span>
						<?php else: ?>
							<span style="color: red"> Sold</span>
							
						<?php endif; ?>
					</a>
					
			</div>
			
			
		<?php endforeach; ?>
	<?php endif; ?>




<?php require_once 'includes/footer.php'; ?>