<?php
session_start();
require_once '../includes/db.php';

// Delete token from database if it exists
if (isset($_COOKIE['remember_me'])) {
	// code...
	$token = $_COOKIE['remember_me'];

	$stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token = :token");
	$stmt->execute([':token' => $token]);

	// Delete the cookie setting expiry in the past
	setcookie('remember_me', '', time() - 3600, '/');
}

session_destroy();
header('Location: /admin/login.php');
exit();

?>