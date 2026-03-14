<?php
// Admin Dashboard
require_once '../includes/auth.php';

echo "Welcome: " . $_SESSION['name'];
?>

<section class="content">
	<div class="logout">
		<a href="logout.php">Logout</a>
	</div>
</section>