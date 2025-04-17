<?php
session_start();
require './databasse/db.php';

if (!isset($_GET['id'])) {
    echo "Missing hotel ID.";
    exit;
}

$hotel_id = (int)$_GET['id'];

// 🔁 1. Ștergem imaginile asociate
$stmt = $conn->prepare("DELETE FROM hotel_images WHERE hotel_id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$stmt->close();

// 🔁 2. Ștergem ratingurile asociate
$stmt = $conn->prepare("DELETE FROM ratings WHERE hotel_id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$stmt->close();

// 🔁 3. Ștergem rezervările asociate
$stmt = $conn->prepare("DELETE FROM bookings WHERE hotel_id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$stmt->close();

// 🔁 4. Ștergem hotelul
$stmt = $conn->prepare("DELETE FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$stmt->close();

header("Location: my_hotels.php?deleted=success");
exit;
?>
