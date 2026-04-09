<?php
// Single property detail page
require_once 'includes/db.php';
// Home page listing all properties
require_once 'includes/functions.php';
require_once 'includes/classes/Property.php';

//Declare a variable to hold data that will be fetched 
//from the db and assign it to null
$data = '';
// check if the id exist in the URL
if(isset($_GET['id'])){
	//validate it is a number
	$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

	// If its not a valid integer 
	if ($id === false) {
		// redirect back to index page
		header('Location: index.php');
		exit();
	}

	// Now we know id is a safe number
	$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = :id AND deleted_at IS NULL");
	$stmt->execute([':id' => $id]);
	//fetch the row as an associative array
	$data = $stmt->fetch();

	//If data doesn't exist
	if(!$data){
		header('Location: index.php');
		exit();
	}

}

$property = new Property($data);

// 

$pageTitle = htmlspecialchars('Property');
require_once 'includes/header.php';

?>

<section class="content">
		<div class="propertyById">
		<!-- If data exist display it by it id -->
		<?php if($property) : ?>
			<h2><?php echo htmlspecialchars($property->getTitle()); ?></h2>
			<p class="propTag">
				<?php if ($property->isAvailable()): ?>
					<span style="color:green"> - Available</span>
				<?php else: ?>
					<span style="color:red">Sold</span>
				<?php endif;?>
			</p>
			<p><?php echo $property->getFormattedPrice();?></p>
			<p><?php echo htmlspecialchars($property->getLocation());?></p>
			<p><?php echo htmlspecialchars($property->getStatus());?></p>
			<p><img src="assets/images/<?php echo $property->getImage(); ?>" height="50" width="50">
				</p>

			<!-- if it doesn't, tell the user property wasn't found -->
		<?php else: ?>
			<?php echo "Property not found" ?>
		<?php endif;?>
	</div>
	
</section>


<?php require_once 'includes/footer.php'; ?>