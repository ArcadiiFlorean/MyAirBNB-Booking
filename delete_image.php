<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

if (!isset($_GET['image_id']) || !isset($_GET['hotel_id'])) {
    echo "Missing parameters.";
    exit;
}

$image_id = (int)$_GET['image_id'];
$hotel_id = (int)$_GET['hotel_id'];
$user_id = $_SESSION['user_id'];

// Verificăm dacă hotelul aparține userului
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $hotel_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Access denied or hotel not found.";
    exit;
}

// Obținem imaginea
$img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE id = ? AND hotel_id = ?");
$img_stmt->bind_param("ii", $image_id, $hotel_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();

if ($img_result->num_rows === 1) {
    $img = $img_result->fetch_assoc();
    if (file_exists($img['image_path'])) {
        unlink($img['image_path']); // Șterge fișierul fizic
    }

    // Șterge din DB
    $del_stmt = $conn->prepare("DELETE FROM hotel_images WHERE id = ?");
    $del_stmt->bind_param("i", $image_id);
    $del_stmt->execute();
    $del_stmt->close();
}

$img_stmt->close();

// Redirecționează înapoi la pagina de editare
header("Location: edit_hotel.php?id=$hotel_id");
exit;
