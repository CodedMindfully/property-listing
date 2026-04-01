
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
	<script type="text/javascript" src="/assets/js/main.js"></script>
</head>
<body>
	<!-- Admin sidebar -->
	<!-- If the variable is $isAdmin is called and its an admin page -->
	<?php if (isset($isAdmin) && $isAdmin) : ?>
		<aside class="">
			<a href="/admin/index.php">Dashboard</a>
			<a href="/admin/add-property.php">Add Property</a>
			<a href="/admin/deleted.php">Recently Deleted</a>
			<!-- Opens public homepage in new tab -->
			<a href="/index.php" target="_blank">View Site</a>
			<a href="/admin/logout.php">Logout</a>
		</aside>
		
	<?php else: ?>
		<!-- Public navigation -->
		<nav class="navbar">
			<a href="/index.php">Home</a>
			<a href="/search.php">Search Properties</a>
		</nav>
	<?php endif; ?>