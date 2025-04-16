<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];

$hotel_id = $_POST['hotel_id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$price = $_POST['price'];
$fee = $_POST['card_fee'];
$discount = $_POST['discount'];
$vat = $_POST['vat'];

// Gestionăm imaginea principală (optional)
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $imagePath = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

    $stmt = $conn->prepare("UPDATE hotels SET title = ?, description = ?, price_per_day = ?, card_fee = ?, discount_percentage = ?, vat_percentage = ?, image_path = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssdddisii", $title, $desc, $price, $fee, $discount, $vat, $imagePath, $hotel_id, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE hotels SET title = ?, description = ?, price_per_day = ?, card_fee = ?, discount_percentage = ?, vat_percentage = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssddiiii", $title, $desc, $price, $fee, $discount, $vat, $hotel_id, $user_id);
}

if ($stmt->execute()) {

    // ✅ Adaugă imagini multiple (dacă au fost trimise)
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['images']['error'][$index] === 0 && is_uploaded_file($tmpName)) {
                $fileName = time() . '_' . basename($_FILES['images']['name'][$index]);
                $filePath = 'uploads/' . $fileName;

                if (move_uploaded_file($tmpName, $filePath)) {
                    $imgStmt = $conn->prepare("INSERT INTO hotel_images (hotel_id, image_path) VALUES (?, ?)");
                    $imgStmt->bind_param("is", $hotel_id, $filePath);
                    $imgStmt->execute();
                    $imgStmt->close();
                }
            }
        }
    }

    // ✅ Abia acum redirect
    header("Location: my_hotels.php?edited=success");
    exit;

} else {
    echo "Error updating hotel: " . $stmt->error;
}
