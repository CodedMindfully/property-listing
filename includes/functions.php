<?php
// All my functions goes here

// Display property
function displayProperties($property){

	$name = htmlspecialchars($property['name']);
	$price = number_format($property['price']);
	$location = htmlspecialchars($property['location']);
	$status = htmlspecialchars($property['status']);
	// format the date to be more human friendly
	//F = full month name (January, February etc)
	// j = day of the month
	//Y = 4 digit year
	$date = date("F j, Y", strtotime($property['created_at']));

	// Build the output string and use .= operator to attach
	//a next line to what is already in the $outpud variable
	$output = "Property name: " . $name . "<br>";
	$output .= "Property price: £" . $price . "<br>";
	$output .= "Property location: " . $location . "<br>";
	$output .= "Property status: " . $status . "<br>";
	$output .= "Listed on: " . $date . "<br><br>";

	// return the output
	return $output;

}