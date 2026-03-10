<?php
// All my functions goes here

// Display property
function displayProperty($property){
	echo "Property name: " . $property['name'] . "<br>";
	echo "Property price: £:" . $property['price'] . "<br>";
	echo "Property location: " . $property['location'] . "<br>";
	echo "Property status: " . $property['status'] . "<br><br>";
}