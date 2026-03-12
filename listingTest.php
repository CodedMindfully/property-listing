<?php
  require_once 'includes/db.php';
  require_once 'includes/functions.php';

  // initialise variables
  $minPrice = '';
  $maxPrice = '';
  $location = '';
  $status   = '';
  $results  = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitise inputs
    $minPrice = htmlspecialchars($_POST['minPrice']);
    $maxPrice = htmlspecialchars($_POST['maxPrice']);
    $location = htmlspecialchars($_POST['location']);
    $status   = htmlspecialchars($_POST['status']);

    // build dynamic query
    $conditions = [];
    $params     = [];

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
      $params[':location'] = "%" . $location . "%";
    }

    if (!empty($status)) {
      $conditions[] = "status = :status";
      $params[':status'] = $status;
    }

    $sql = "SELECT * FROM properties";

    if (!empty($conditions)) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search — <?php echo SITE_NAME; ?></title>
</head>
<body>

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

    <label>Status</label>
    <select name="status">
      <option value="">Any</option>
      <option value="Available" <?php echo $status === 'Available' ? 'selected' : ''; ?>>Available</option>
      <option value="Sold" <?php echo $status === 'Sold' ? 'selected' : ''; ?>>Sold</option>
    </select>

    <br><br>

    <button type="submit">Search</button>
  </form>

  <hr>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <h2>Results</h2>
    <?php if (empty($results)): ?>
      <p>No properties found matching your search.</p>
    <?php else: ?>
      <p><?php echo count($results); ?> properties found</p>
      <?php foreach ($results as $property): ?>
        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
          <?php displayProperties($property); ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>

</body>
</html>