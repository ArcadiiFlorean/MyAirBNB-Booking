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

// ✅ Verificăm dacă perioada e deja rezervată
$checkStmt = $conn->prepare("
    SELECT * FROM bookings 
    WHERE hotel_id = ? 
    AND (
        (checkin_date <= ? AND checkout_date > ?) OR
        (checkin_date < ? AND checkout_date >= ?) OR
        (checkin_date >= ? AND checkout_date <= ?)
    )
");
$checkStmt->bind_param("issssss", $hotel_id, $checkout, $checkin, $checkin, $checkout, $checkin, $checkout);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo "This period is already booked!";
    exit;
}
$checkStmt->close();

// ✅ Obținem informațiile hotelului pentru calcule
$priceStmt = $conn->prepare("SELECT price_per_day, card_fee, discount_percentage, vat_percentage FROM hotels WHERE id = ?");
$priceStmt->bind_param("i", $hotel_id);
$priceStmt->execute();
$priceStmt->bind_result($price_per_day, $card_fee, $discount_percent, $vat_percent);
$priceStmt->fetch();
$priceStmt->close();

// ✅ Calcul subtotal, discount, TVA, total
$subtotal = $price_per_day * $days;
$discount = ($days >= 14) ? ($subtotal * $discount_percent / 100) : 0;
$vat = ($subtotal - $discount) * $vat_percent / 100;
$total = $subtotal - $discount + $card_fee + $vat;

// ✅ Salvăm rezervarea
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
?>
