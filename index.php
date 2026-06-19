<?php
require 'config.php';

$search_query = "";
if(isset($_GET['search'])) {
    $search_query = $_GET['search'];
}
$cuisine_filter = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';

$sql = "SELECT * FROM restaurants WHERE 1=1";
$params = [];

if ($search_query != '') {
    $sql .= " AND name LIKE :search";
    $params[':search'] = "%$search_query%";
}
if ($cuisine_filter != '') {
    $sql .= " AND cuisine = :cuisine";
    $params[':cuisine'] = $cuisine_filter;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$restaurants = $stmt->fetchAll();

$cuisine_stmt = $pdo->query("SELECT DISTINCT cuisine FROM restaurants");
$cuisines = $cuisine_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Restaurants</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-img-top { height: 200px; object-fit: cover; }
</style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Restaurant Reviews</a>
    </div>
</nav>

<div class="container">
    <h1 class="text-center mb-4">Find Your Next Meal</h1>

    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="index.php" class="row">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by restaurant name..." value="<?= htmlspecialchars($search_query) ?>">
                </div>
                <div class="col-md-4">
                    <select name="cuisine" class="form-select">
                        <option value="">All Cuisines</option>
                        <?php foreach ($cuisines as $c) { ?>
                            <option value="<?= htmlspecialchars($c) ?>" <?php if($cuisine_filter == $c) echo 'selected'; ?>>
                                <?= htmlspecialchars($c) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (count($restaurants) > 0) { ?>
            <?php foreach ($restaurants as $r) { ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($r['image_url']) ?>" class="card-img-top">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($r['name']) ?></h5>
                            <span class="badge bg-secondary"><?= htmlspecialchars($r['cuisine']) ?></span>
                            <p class="mt-2"><?= htmlspecialchars($r['location']) ?></p>
                            <p><?= htmlspecialchars($r['description']) ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="details.php?id=<?= $r['id'] ?>" class="btn btn-primary w-100">Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-12"><p>No restaurants found.</p></div>
        <?php } ?>
    </div>
</div>
</body>
</html>
