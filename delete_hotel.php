<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Hotel ID missing.";
    exit;
}

$hotel_id = intval($_GET['id']);

// 1️⃣ Șterge întâi facilitățile
$stmt1 = $conn->prepare("DELETE FROM hotel_facilities WHERE hotel_id = ?");
$stmt1->bind_param("i", $hotel_id);
$stmt1->execute();
$stmt1->close();

// 2️⃣ Șterge imaginile (dacă ai și în `hotel_images`)
$stmt2 = $conn->prepare("DELETE FROM hotel_images WHERE hotel_id = ?");
$stmt2->bind_param("i", $hotel_id);
$stmt2->execute();
$stmt2->close();

// 3️⃣ Acum ștergi hotelul
$stmt3 = $conn->prepare("DELETE FROM hotels WHERE id = ?");
$stmt3->bind_param("i", $hotel_id);
$stmt3->execute();
$stmt3->close();

header("Location: my_hotels.php");
exit;
?>
