<?php
require './databasse/db.php';

$hotel_id = $_POST['hotel_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$adults = $_POST['adults'];
$children = $_POST['children'];
$pets = $_POST['pets'];

// Calcul zile
$checkinDate = new DateTime($checkin);
$checkoutDate = new DateTime($checkout);
$days = $checkoutDate->diff($checkinDate)->days;

$stmt = $conn->prepare("SELECT price_per_day, card_fee, discount_percentage, vat_percentage FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$stmt->bind_result($price_per_day, $card_fee, $discount_percent, $vat_percent);
$stmt->fetch();
$stmt->close();

// Calcul subtotal
$subtotal = $price_per_day * $days;
$discount = ($days >= 14) ? ($subtotal * $discount_percent / 100) : 0;
$vat = ($subtotal - $discount) * $vat_percent / 100;
$total = $subtotal - $discount + $card_fee + $vat;

// Salvează rezervarea
$insert = $conn->prepare("INSERT INTO bookings 
(hotel_id, customer_name, phone, checkin_date, checkout_date, days, subtotal, card_fee, discount, vat, total, adults, children, pets) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param(
    "issssddddddiis",
    $hotel_id, $name, $phone, $checkin, $checkout,
    $days, $subtotal, $card_fee, $discount, $vat, $total,
    $adults, $children, $pets
);
$insert->execute();
$insert->close();

echo "Booking successful! <a href='index.php'>Back to Home</a>";
