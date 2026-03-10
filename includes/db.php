<?php
//DB connections
/**__DIR__ is a php magic constant that returns the full path of the file it's written in. So no matter what file includes db.php, the path to config.php is always calculated correctly
 **/

require_once __DIR__ . '/../config.php';
// var_dump(DB_HOST);
// var_dump(DB_NAME);
// var_dump(DB_USER);
// var_dump(DB_PASS);

$dns = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";

try{
	$pdo = new PDO($dns, DB_USER, DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	// echo "Connection Successful!";
}catch(PDOException $e){
	die("Connection Failed: " . $e->getMessage());
}