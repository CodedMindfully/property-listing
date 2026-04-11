<?php
// Tell the browser/client this response is JSON 
header('Content-type: application/json');

// Allow cross origin request 
header('Access-Control-Allow-Origin: *');

// include db
require_once '../includes/db.php';

// include Property Class
require_once '../includes/classes/Property.php';

// Initialise $id 
$id = null;
// Only allow GET request to this endpoint
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	// Method not allow
	http_response_code(405);
	echo json_encode([
		'success' => false, 
		'message' => 'Method not allowed'
	]);
	exit();

}

// check that the id is present 
if (isset($_GET['id'])) {
	// validate it an integer
	$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
	// if its not a valid integer return 404
	if ($id === false) {
		// 
		http_response_code(404);
		echo json_encode([
			'success' => false,
			'message' => 'Property not found']);
		exit();
	}
}else{
	// if an id was not presented
	http_response_code(400);
	echo json_encode([
		'success'	=> false,
		'message'	=> 'Your request cannot be completed.'

	]);
	exit();
}

// If id exist and its an integer
try{
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
		WHERE p.id = :id
			AND p.deleted_at IS NULL
		LIMIT 1"
		);
	$stmt->execute([':id' => $id]);
	// fetch the row as an associative array
	$row = $stmt->fetch();

	// If the data does not exist
	if(!$row){
		http_response_code(404);
		echo json_encode([
			'success'	=> false,
			'message'	=> 'Property not found']);
			exit();
	}

	// Create an object 
	$property = new Property($row);
	// create a new array for the json response
	$data = [
		'id'		=> $property->getId(),
		'title'		=> $property->getTitle(),
		'price'		=> $property->getFormattedPrice(),
		'location'	=> $property->getLocation(),
		'status'	=> $property->getStatus(),
		'image'		=> $property->getImage(),
		'listed_by'	=> $property->getListedBy(),
		'listed_date'	=> $property->getListedDate()
	];

	// Send back a success response with the data
	http_response_code(200);
	echo json_encode([
		'success' => true,
		'data'    => $data]);

}catch(PDOException $e){
	// server error 
	http_response_code(500);
	echo json_encode([
		'success'	=> false,
		'message'	=> 'Failed to fetch property'
	]);
}

?>