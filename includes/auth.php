<?php
session_start();

  // // temporary debug
  // var_dump($_COOKIE);
  // var_dump($_SESSION);
  // die();

require_once __DIR__ . '/../includes/db.php';
//if session is not set
// Automatically log in users with a valid remember me cookie.
// If there's no active session
if(!isset($_SESSION['admin_id'])){
	//no active session check for remember me cookie
	if (isset($_COOKIE['remember_me'])) {
		// create a token variable and assign it to the remember me cookie
		$token = $_COOKIE['remember_me'];

		// look up token in database
		//expires_ay > NOW() check the token hasn't expired directly in the SQL query
		// NOW() is a MySQL function, returns current datetime
		$stmt = $pdo->prepare("SELECT * FROM remember_tokens
								WHERE token = :token
								AND expires_at > NOW()");
		$stmt->execute([':token' => $token]);
		$remember = $stmt->fetch();

		//If the token exist
		if ($remember) {
			// valid token found, log them in automatically
			session_regenerate_id(true);
			//Assign this admin to their token
			$_SESSION['admin_id'] = $remember['admin_id'];

			// fetch admin details
			$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = :id");
			$stmt->execute([':id' => $remember['admin_id']]);
			$admin = $stmt->fetch();
			$_SESSION['name'] = $admin['name'];
		}else{
			//Redirect invalid or expired token to login
			header('Location: /admin/login.php');
			exit();
		}
	}else{
		//Redirect to login when there's no session and no cookie
		header('Location: /admin/login.php');
		exit();
	}
}

?>