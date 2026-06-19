<?php
require 'config.php';

if (!isset($_GET['id'])) {
    die("Error");
}
$restaurant_id = $_GET['id'];
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    if ($customer_name == "" || $email == "" || $review_text == "" || $rating == "") {
        $error_msg = "Please fill all fields.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email.";
    } else {
        $sql = "INSERT INTO reviews (restaurant_id, customer_name, email, rating, review_text) VALUES (:restaurant_id, :customer_name, :email, :rating, :review_text)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':restaurant_id' => $restaurant_id, ':customer_name' => $customer_name, ':email' => $email, ':rating' => $rating, ':review_text' => $review_text]);
        header("Location: details.php?id=$restaurant_id&success=1");
        exit;
    }
}

if (isset($_GET['success'])) {
    $success_msg = "Review submitted!";
}

$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = :id");
$stmt->execute([':id' => $restaurant_id]);
$restaurant = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT * FROM reviews WHERE restaurant_id = :id ORDER BY id DESC");
$stmt2->execute([':id' => $restaurant_id]);
$reviews = $stmt2->fetchAll();

$avg = 0;
if (count($reviews) > 0) {
    $sum = 0;
    foreach($reviews as $rev) {
        $sum += $rev['rating'];
    }
    $avg = round($sum / count($reviews), 1);
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?= htmlspecialchars($restaurant['name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <a href="index.php" class="btn btn-secondary mb-3">Back</a>
    <a href="edit-restaurant.php?id=<?= $restaurant['id'] ?>" class="btn btn-warning mb-3 float-end">Edit</a>

    <div class="card mb-4">
        <div class="card-body">
            <h1><?= htmlspecialchars($restaurant['name']) ?></h1>
            <p>Cuisine: <?= htmlspecialchars($restaurant['cuisine']) ?></p>
            <p>Rating: <?= $avg > 0 ? $avg : "No ratings" ?></p>
            <hr>
            <p><?= htmlspecialchars($restaurant['description']) ?></p>
            <p>Location: <?= htmlspecialchars($restaurant['location']) ?></p>
            <p>Hours: <?= htmlspecialchars($restaurant['opening_hours']) ?></p>
        </div>
    </div>

    <?php if($success_msg != "") echo "<div class='alert alert-success'>$success_msg</div>"; ?>
    <?php if($error_msg != "") echo "<div class='alert alert-danger'>$error_msg</div>"; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="details.php?id=<?= $restaurant_id ?>" id="reviewForm">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label>Rating</label>
                            <select class="form-select" name="rating" required>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Review</label>
                            <textarea class="form-control" name="review_text" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Reviews</h3>
            <?php foreach ($reviews as $r) { ?>
                <div class="card mb-2">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($r['customer_name']) ?> (<?= $r['rating'] ?>/5)</h5>
                        <p><?= htmlspecialchars($r['review_text']) ?></p>
                        <form action="delete-review.php" method="POST">
                            <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                            <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
document.getElementById('reviewForm').addEventListener('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault();
        alert('Please fill out all fields correctly.');
    }
});
</script>
</body>
</html>
