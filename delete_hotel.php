<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Missing hotel ID.";
    exit;
}

$hotel_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Verificăm dacă hotelul aparține userului
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $hotel_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Hotel not found or access denied.";
    exit;
}

// 🔸 Ștergem imaginile fizic de pe server
$img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
$img_stmt->bind_param("i", $hotel_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();

while ($img = $img_result->fetch_assoc()) {
    if (file_exists($img['image_path'])) {
        unlink($img['image_path']);
    }
}
$img_stmt->close();

// 🔸 Ștergem înregistrările din hotel_images
$del_imgs_stmt = $conn->prepare("DELETE FROM hotel_images WHERE hotel_id = ?");
$del_imgs_stmt->bind_param("i", $hotel_id);
$del_imgs_stmt->execute();
$del_imgs_stmt->close();

// 🔸 Ștergem hotelul
$del_hotel_stmt = $conn->prepare("DELETE FROM hotels WHERE id = ? AND user_id = ?");
$del_hotel_stmt->bind_param("ii", $hotel_id, $user_id);
$del_hotel_stmt->execute();
$del_hotel_stmt->close();

// ✅ Redirecționează înapoi
header("Location: my_hotels.php?deleted=success");
exit;
