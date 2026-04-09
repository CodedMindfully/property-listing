<?php
  require_once 'includes/db.php';
  require_once 'includes/functions.php';
  require_once 'includes/classes/Property.php';

// initialise variables
//This is done to prevent html from throwing errors because
  //the page doesn't recognise these variables.
  //This variables will be used later
  $minPrice = '';
  $maxPrice = '';
  $location = '';
  $status   = '';
  $rows  = [];

// If the user clicks on search botton 
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitise inputs to prevent Cross Site Scripting (XSS)
    $minPrice = htmlspecialchars($_POST['minPrice']);
    $maxPrice = htmlspecialchars($_POST['maxPrice']);
    $location = htmlspecialchars($_POST['location']);
    $status   = htmlspecialchars($_POST['status']);

    // build dynamic query
    // $conditions handles set of rules e.g. price must be at least X amount
    $conditions = [];
    // $params handles a list of actual values
    $params     = [];

    //: before values e.g. :minPrice acts as a placeholder
    //instead of putting the user's data directly into the SQL
    //which is dangerous, I put a label there and fill it in later
    //to prevent SQL injecttion

    if (!empty($minPrice)) {
      $conditions[] = "price >= :minPrice";
      $params[':minPrice'] = $minPrice;
    }

    if (!empty($maxPrice)) {
      $conditions[] = "price <= :maxPrice";
      $params[':maxPrice'] = $maxPrice;
    }

    if (!empty($location)) {
      $conditions[] = "location LIKE :location";
      // using the wirldcard % with the LIKE operator 
      //to find text that contains whatever the user types in location
      //no matter what comes before or after them
      //% location % 
      $params[':location'] = "%" . $location . "%";
    }

    if (!empty($status)) {
      $conditions[] = "status = :status";
      $params[':status'] = $status;
    }

//Get everything from the properties table
    $sql = "SELECT * FROM properties";

	//If the user types anything in the input and option fields
	//add a WHERE clause
    //implode(" AND ", $conditions) takes list of rules and glue
    //them together with the word AND
    //e.g. a search for min price and location becomes
    //WHERE price >= :minPrice AND location LIKE :location
    if (!empty($conditions)) {
      $sql .= " WHERE " . implode(" AND ", $conditions) . " AND deleted_at IS NULL";
    }

    //send a template (a placeholder) of my SQL command to the database
    $stmt = $pdo->prepare($sql);
    //execute($params) sends actual data to fill in the placeholders
    $stmt->execute($params);
    //fetchAll() grab matching rows and save them into $rows array
    $rows = $stmt->fetchAll();

  }

  // turn each database row into an object
  $properties = [];

  // Loop through the property $rows
  foreach ($rows as $row) {
    // store the rows in the results array
    $properties [] = new Property($row);
  }


//Assign a page title for search page
//This variable has been called in <title> in the header.php
$pageTitle = htmlspecialchars('Search');
require_once 'includes/header.php'; 


?>

  <h1><?php echo SITE_NAME; ?></h1>
  <a href="index.php">← Back to listings</a>

  <h2>Search Properties</h2>

  <form method="POST" action="">
    <label>Min Price</label>
    <input type="number" name="minPrice" value="<?php echo $minPrice; ?>">

    <br><br>

    <label>Max Price</label>
    <input type="number" name="maxPrice" value="<?php echo $maxPrice; ?>">

    <br><br>

    <label>Location</label>
    <input type="text" name="location" value="<?php echo $location; ?>">

    <br><br>

    <label for="status">Status</label>
    <select name="status">
      <option value="">Any</option>
      <!-- Use of Ternary Operator (one line if statement). If the current status is 'Available'
      add the word selected to the HTML so this option stays highlighted -->
      <option value="Available" <?php echo $status === 'Available' ? 'selected' : ''; ?>>Available</option>
      <option value="Sold" <?php echo $status === 'Sold' ? 'selected' : ''; ?>>Sold</option>
    </select>

    <br><br>

    <button type="submit">Search</button>
  </form>

  <hr>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <h2>Results</h2>
    <?php if (empty($properties)): ?>
      <p>No properties found matching your search.</p>
    <?php else: ?>
      <p><?php echo count($properties); ?> properties found</p>
      <?php foreach ($properties as $property): ?>
        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
          <h2><?php echo htmlspecialchars($property->getTitle()); ?></h2>
          <p><?php echo $property->getFormattedPrice(); ?></p>
          <p><?php echo htmlspecialchars($property->getLocation()); ?></p>
          <p><?php echo $property->getStatus(); ?></p>
          <p><img src="assets/images/<?php echo $property->getImage(); ?>" height="50" width="50"></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>


<?php require_once 'includes/footer.php'; ?>