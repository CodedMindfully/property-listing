<?php 
session_start();
// Prevent browser from catching this page
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// if already logged in redirect to dashboard
if(isset($_SESSION['admin_id'])){
	header('Location: index.php');
	exit();
}
require_once '../includes/db.php';

$email = '';
$password = '';
$remember = '';
$errors = [];


//if user clicks on the login button
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// remove accidental spaces by using trim()
	$email = trim($_POST['email']);
	$password = $_POST['password'];

	//if email and password fields are empty
	if(empty($email) || empty($password)){
		// enter the error in the errors array
		$errors [] = "Email and password fields cannot be empty.";
		//check if email is in the correct format
	}elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// error message
		$errors [] = "Invalid email format";
	}

	if (empty($errors)) {
		// Query the admin table by email 
		$query = "SELECT * FROM admins WHERE email = :email";
		$stmt = $pdo->prepare($query);
		$stmt->execute([':email' => $email]);
		$admin = $stmt->fetch();

		//Verify user exists and password matches the db hash
		if ($admin && password_verify($password, $admin['password'])) {
			// Success
			//Add session fixation protection 
			//prevent session hijack
			session_regenerate_id(true); //Prevent session fixaton attacks
			$_SESSION['admin_id'] = $admin['id'];
			$_SESSION['name'] = $admin['name'];
			// Generate cookie
			// If the checkbox for remember me is checked
			if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
				// Generate a random unguessable token, good for security
				$token = bin2hex(random_bytes(32));

				//set expiry to 30 days from now 
				$expiry = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

				//Save token to database
				$stmt = $pdo->prepare("INSERT INTO remember_tokens (admin_id, token, expires_at)
										VALUES (:admin_id, :token, :expires_at)");
				$stmt->execute([
					':admin_id' => $admin['id'],
					':token'   => $token,
					':expires_at'  => $expiry
				]);

				// set cookie in browser for 30 days
				// cookie name, token saved in db, time () + 30 days, '/' path available across entire site
				//'' domain, false https only set to true in product
				//true httponly prevent javascript from accessing it and protects against XSS
				setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
			}
			header('Location: index.php');
			exit();
		}else{
			//Generic error message intentionally not telling which one is wrong
			$errors [] = "Invalid email or password.";
		}
    } 

}


?>

<?php
$pageTitle = "Admin Login";
require_once '../includes/header.php';

?>


<section class="content">

	<div class="loginForm">
		<?php if (!empty($errors)) : ?>
			<div class="displayErrors">
				<ul style="margin: 0;">
					<?php foreach($errors as $error) : ?>
						<li><?php echo htmlspecialchars($error); ?></li>
					<?php endforeach; ?>
				</ul>

			</div>
		<?php endif; ?>

		<form method="post" action="">
			<div class="formData">
				<label for="email" class="formLabel">Email</label>
				<input type="text" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
			</div>
			<div class="formData">
				<label for="password" class="formLabel">Password</label>
				<input type="password" name="password" placeholder="Enter password">
			</div>
			<div class="formData">
				<input type="submit" name="submit" value="Login" class="btn">
			</div>
			<div class="formData">
				<label class="">
					<input type="checkbox" name="remember"> Remember me
				</label>
				
			</div>
		</form>
		
	</div>
	
</section>