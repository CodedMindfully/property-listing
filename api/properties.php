<?php

// Tell the browser/client this response is JSON not HTML 
header('Content-type: application/json');

// Allow cross origin request (this is needed for mobile apps and React frontends)
header('Access-Control-Allow-Origin: *');

// include db
require_once '../includes/db.php';

// include the Property class 
require_once '../includes/classes/Property.php';

// Only allow GET request to this endpoint
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	// Method not allowed
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Method not allowed']);
	exit();
}

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
		WHERE p.deleted_at IS NULL
		ORDER BY p.created_at DESC"
	);
	$stmt->execute();
	$rows = $stmt->fetchAll();

	// turn each row into a Property object
	$properties = [];
	foreach ($rows as $row) {
		// code...
		$property = new Property($row);
		// Build a clean array for JSON output 
		$properties [] = [
			'id' 			=> $property->getId(),
			'title'			=> $property->getTitle(),
			'price'			=> $property->getFormattedPrice(),
			'location'		=> $property->getLocation(),
			'status'		=> $property-> getStatus(),
			'image'			=> $property -> getImage(),
			'listed_by'		=> $property-> getListedBy(),
			'listed_date'	=> $property-> getListedDate()
		];
	}

	// Send back a success response with the data
	http_response_code(200);
	echo json_encode([
		'success' => true,
		'count'	  => count($properties),
		'data'	  => $properties

	]);
}catch(PDOException $e){
	// server error 
	http_response_code(500);
	echo json_encode([
		'success'	=> false,
		// Use this message instead of $e->getMessage() 
		// to prevent information leakage as PDO error message might contain 
		//db infos like db name, table structure or server details.
		'message'	=> 'Failed to fetch properties'
	]);
}



?>