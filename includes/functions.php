<?php
// All my functions goes here

// Display property
function displayProperties($property){

	$name = htmlspecialchars($property['name']);
	$price = number_format($property['price']);
	$location = htmlspecialchars($property['location']);
	$status = htmlspecialchars($property['status']);
	$image = $property['image'];
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


// Cleaning function
function cleanInputs($data){
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Helper function for displaying prices
// If the price is a whole number, show zero decimals else show 2
function formatPrice($price){
	// If price divided by 1 remainder 0 its a whole number
	//else its decimal is kept
	$decimals = ($price % 1 == 0) ? 0 : 2;
	return number_format($price, $decimals);
}

function uploadImage($fileInputName = 'image'){
	// directory where the file is going to be placed
	$targetDir = '../assets/images/';
	// array that holds the accepted file extensions
	$allowedExt = ['png', 'jpeg', 'jpg', 'gif'];
	$errors = [];
	// Size of image is 2MB
	$maxFileSize = 2 * 1024 * 1024;
	$tempPath = $_FILES[$fileInputName]['tmp_name'];


	// Is the file completely missing from the request?
	// OR Did something go wrong during the transfer?
	// Exiit if the file wasn't sent or if the upload failed at the server level
	if(!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK){
		$errorCode = $_FILES[$fileInputName]['error'];
		// Switch through the errors to catch transfer failure
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE:
				// code...
				return 'Error: The file is too large for the server to handle';
			case UPLOAD_ERR_PARTIAL:
				// code...
				return 'Error: The upload was interupted. Please try again';
			case UPLOAD_ERR_NO_FILE:
				return 'Error: No file was selected.';			
			default:
			return 'Error: Upload failed. System error code: ' . $errorCode;
	
		}
		
	}

	// temporary file path
	$tempPath = $_FILES[$fileInputName]['tmp_name'];
	// Main file name
	// To prevent malicious user from tricking the script into working with files it shouldn't touch 
	//Check that the file was uploaded through HTTP POST request
	if (!is_uploaded_file($tempPath)) {
		return 'Error: Your image type is not supported';
	}
	// Validate
	$fileName = $_FILES[$fileInputName]['name'];
	// convert the file name and extension to lower case and get extension
	$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	// validate extension
	if (!in_array($fileExt, $allowedExt)) {
		// code...
		return 'Error: ' . $fileExt . ' is not a supported file type.';
	}
	// validate file size
	if (filesize($tempPath) > $maxFileSize) {
		// code...
		return 'Error: The file is too large. Maximum size is 2MB';
	}
	// validate mime type 
	$mimeType = mime_content_type($tempPath);
	if (strpos($mimeType, 'image/') !== 0) {
		// code...
		return 'Error: File content does not appear to be an actual image.';
	}
	// Move and rename file
	$newFileName = bin2hex(random_bytes(16)) . '.' . $fileExt;
	$destPath = $targetDir . $newFileName;
	if (move_uploaded_file($tempPath, $destPath)) {
		// code...
		return $newFileName;
	}else{
		return 'Error: Could not save the file to the server.';
	}

}


function showFlashMessages(){
	if (isset($_SESSION['success'])) {
		// code...
		echo '<p style="color:green;">' . htmlspecialchars($_SESSION['success']) . '</p>';
		unset($_SESSION['success']);
	}

	if (isset($_SESSION['error'])) {
		// code...
		echo '<p style="color:red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
		unset($_SESSION['error']);
	}
}
