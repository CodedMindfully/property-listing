<?php
// Single property detail page
require_once 'includes/db.php';
// Home page listing all properties
require_once 'includes/functions.php';

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
	$query = "SELECT * FROM properties WHERE id = :id AND deleted_at IS NULL";
	$stmt = $pdo->prepare($query);
	$stmt->execute([':id' => $id]);
	//fetch the row as an associative array
	$data = $stmt->fetch();

	//If data doesn't exist
	if(!$data){
		header('Location: index.php');
		exit();
	}

}

$pageTitle = htmlspecialchars('Property');
require_once 'includes/header.php';

?>

<section class="content">

		<!-- If data exist display it by it id -->
		<?php if($data) : ?>
			<p>
				<?php echo displayProperties($data); ?>
			</p>

			<!-- if it doesn't, tell the user property wasn't found -->
		<?php else: ?>
			<?php echo "Property not found" ?>
		<?php endif;?>

	
</section>


<?php require_once 'includes/footer.php'; ?>