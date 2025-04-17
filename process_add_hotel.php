<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$price = $_POST['price'];
$fee = $_POST['card_fee'];
$discount = $_POST['discount'];
$vat = $_POST['vat'];
$max_guests = $_POST['max_guests']; // ✅ nou

// Prima imagine (optională, ca thumbnail)
$imagePath = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imagePath = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
}

// 1️⃣ Salvează hotelul cu max_guests
$stmt = $conn->prepare("INSERT INTO hotels (user_id, title, description, price_per_day, card_fee, discount_percentage, vat_percentage, image_path, max_guests) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issdddisi", $user_id, $title, $desc, $price, $fee, $discount, $vat, $imagePath, $max_guests);
$stmt->execute();

// 2️⃣ Obține ID-ul hotelului
$hotel_id = $conn->insert_id;
$stmt->close();

// 3️⃣ Încarcă pozele multiple
if (isset($_FILES['images'])) {
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['images']['error'][$index] === 0) {
            $fileName = time() . '_' . basename($_FILES['images']['name'][$index]);
            $filePath = 'uploads/' . $fileName;
            move_uploaded_file($tmpName, $filePath);

            $imgStmt = $conn->prepare("INSERT INTO hotel_images (hotel_id, image_path) VALUES (?, ?)");
            $imgStmt->bind_param("is", $hotel_id, $filePath);
            $imgStmt->execute();
        }
    }
}

header("Location: my_hotels.php");
exit;
