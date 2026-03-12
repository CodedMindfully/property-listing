<?php
// All my functions goes here

// Display property
function displayProperties($property){
	echo "Property name: " . $property['name'] . "<br>";
	echo "Property price: £:" . number_format($property['price']) . "<br>";
	echo "Property location: " . $property['location'] . "<br>";
	echo "Property status: " . $property['status'] . "<br><br>";
}