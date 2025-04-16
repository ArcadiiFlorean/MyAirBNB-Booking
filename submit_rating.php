<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Login required.";
    exit;
}

$user_id = $_SESSION['user_id'];
$hotel_id = $_POST['hotel_id'];
$rating = (int)$_POST['rating'];
$comment = $_POST['comment'] ?? null;

$stmt = $conn->prepare("INSERT INTO ratings (hotel_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $hotel_id, $user_id, $rating, $comment);
$stmt->execute();

header("Location: index.php?rated=success");
exit;
