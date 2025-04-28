<?php
require './databasse/db.php';

$hotel_id = $_POST['hotel_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$adults = (int)$_POST['adults'];
$children = (int)$_POST['children'];
$pets = $_POST['pets'];

$total_guests = $adults + $children;

$hotelStmt = $conn->prepare("SELECT price_per_day, card_fee, discount_percentage, vat_percentage, max_guests FROM hotels WHERE id = ?");
$hotelStmt->bind_param("i", $hotel_id);
$hotelStmt->execute();
$hotelStmt->bind_result($price_per_day, $card_fee, $discount_percent, $vat_percent, $max_guests);
$hotelStmt->fetch();
$hotelStmt->close();

// Tailwind Header + Custom Animations
echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Result</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@keyframes fade-scale {
  0% { opacity: 0; transform: scale(0.9) translateY(20px); }
  100% { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-fade-scale {
  animation: fade-scale 0.6s ease-out;
}
</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-6">
<div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full space-y-6 text-center animate-fade-scale">';

if ($total_guests > $max_guests) {
    echo "<div class='text-red-600 text-lg font-semibold'>❌ This hotel allows a maximum of $max_guests guests. You selected $total_guests.</div>";
    echo "<a href='book.php?id=$hotel_id' class='mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition transform hover:scale-105 duration-300'>Go Back</a>";
    echo '</div></body></html>';
    exit;
}

// Calculate days
$checkinDate = new DateTime($checkin);
$checkoutDate = new DateTime($checkout);
$days = $checkoutDate->diff($checkinDate)->days;

// Check for overlapping bookings
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
    echo "<div class='text-red-600 text-lg font-semibold'>❌ This period is already booked!</div>";
    echo "<a href='index.php' class='mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition transform hover:scale-105 duration-300'>Back to Home</a>";
    echo '</div></body></html>';
    exit;
}
$checkStmt->close();

// Calcul total
$subtotal = $price_per_day * $days;
$discount = ($days >= 14) ? ($subtotal * $discount_percent / 100) : 0;
$vat = ($subtotal - $discount) * $vat_percent / 100;
$total = $subtotal - $discount + $card_fee + $vat;

// Insert booking
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

// Success message
echo "<div class='text-green-600 text-2xl font-bold'>✅ Booking successful!</div>";
echo "<p class='text-gray-700'>Thank you, <span class='font-semibold'>" . htmlspecialchars($name) . "</span>! Your booking is confirmed for <span class='font-semibold'>$days</span> night(s).</p>";
echo "<a href='index.php' class='mt-6 inline-block bg-green-500 text-white px-6 py-3 rounded hover:bg-green-600 transition transform hover:scale-105 duration-300'>Back to Home</a>";

echo '</div></body></html>';
?>
