<?php
// Build as array of SQL conditions dynamically, 
// only adding what the user actually filled in
$conditions = [];
$params = [];

if(!empty($minPrice)){
	$conditions[] = "price >= :minPrice";
	$params[':minPrice'] = $minPrice;
}

if(!empty($maxPrice)){
	$conditions[] = "price >= :maxPrice";
	$params[':maxPrice'] = $maxPrice;
}

if(!empty($location)) {
	$conditions[] = "location LIKE :location";
	// The % means anything can go here
	// When searching with location, lon can be read as London
	$params[':location'] = "%" . $location . "%";
}

// Build the final query

$sql = "SELECT * FROM properties";

if(!empty($conditions)){
	// implode joins array items into a string with a seperator e.g. minPrice AND location
	$sql .= " WHERE " . implode("AND", $conditions);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();