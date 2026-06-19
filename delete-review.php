<?php
require 'config.php';

if (isset($_POST['review_id']) && isset($_POST['restaurant_id'])) {
    $rev_id = $_POST['review_id'];
    $rest_id = $_POST['restaurant_id'];

    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id AND restaurant_id = :rest_id");
    $stmt->execute([':id' => $rev_id, ':rest_id' => $rest_id]);
    
    header("Location: details.php?id=" . $rest_id);
} else {
    header("Location: index.php");
}
?>
