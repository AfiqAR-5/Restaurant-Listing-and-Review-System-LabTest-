<?php
require 'config.php';

$id = $_GET['id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $cuisine = $_POST['cuisine'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $opening_hours = $_POST['opening_hours'];
    $image_url = $_POST['image_url'];

    if ($name == '' || $cuisine == '' || $description == '') {
        $error = "Name, Cuisine and Description required.";
    } else {
        $sql = "UPDATE restaurants SET name=:name, cuisine=:cuisine, location=:location, description=:description, opening_hours=:opening_hours, image_url=:image_url WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':cuisine' => $cuisine,
            ':location' => $location,
            ':description' => $description,
            ':opening_hours' => $opening_hours,
            ':image_url' => $image_url,
            ':id' => $id
        ]);
        
        header("Location: details.php?id=".$id);
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Restaurant</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="details.php?id=<?= $id ?>" class="btn btn-secondary mb-3">Back</a>
    <div class="card">
        <div class="card-body">
            <h2>Edit Restaurant</h2>
            <?php if($error != '') echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST" action="edit-restaurant.php?id=<?= $id ?>">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Cuisine</label>
                    <input type="text" class="form-control" name="cuisine" value="<?= htmlspecialchars($row['cuisine']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Location</label>
                    <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($row['location']) ?>">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" name="description" required><?= htmlspecialchars($row['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Opening Hours</label>
                    <input type="text" class="form-control" name="opening_hours" value="<?= htmlspecialchars($row['opening_hours']) ?>">
                </div>
                <div class="mb-3">
                    <label>Image URL</label>
                    <input type="text" class="form-control" name="image_url" value="<?= htmlspecialchars($row['image_url']) ?>">
                </div>
                <button type="submit" class="btn btn-warning">Save</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>