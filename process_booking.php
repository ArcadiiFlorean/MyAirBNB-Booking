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

// Luăm informațiile hotelului
$hotelStmt = $conn->prepare("SELECT price_per_day, card_fee, discount_percentage, vat_percentage, max_guests FROM hotels WHERE id = ?");
$hotelStmt->bind_param("i", $hotel_id);
$hotelStmt->execute();
$hotelStmt->bind_result($price_per_day, $card_fee, $discount_percent, $vat_percent, $max_guests);
$hotelStmt->fetch();
$hotelStmt->close();

// Header HTML
echo '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Result</title>
  <script src="https://cdn.tailwindcss.com"></script>


  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
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
<body class="bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center p-6" style="background-image: url(\'./img/Happy-img.jpg\');">

<div class="bg-white p-8 rounded-lg shadow-lg max-w-xl w-full space-y-6 text-center animate-fade-scale">


';

// Verificăm dacă sunt prea mulți oaspeți
if ($total_guests > $max_guests) {
    echo "<div class='text-red-600 text-lg font-semibold'>❌ This hotel allows a maximum of $max_guests guests. You selected $total_guests.</div>";
    echo "<a href='book.php?id=$hotel_id' class='mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition'>Go Back</a>";
    echo '</div></body></html>';
    exit;
}

// Calculăm numărul de zile
$checkinDate = new DateTime($checkin);
$checkoutDate = new DateTime($checkout);
$days = $checkoutDate->diff($checkinDate)->days;

// Verificăm rezervări suprapuse
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
    echo "<a href='index.php' class='mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition'>Back to Home</a>";
    echo '</div></body></html>';
    exit;
}
$checkStmt->close();

// Calcul costuri
$subtotal = $price_per_day * $days;
$discount = ($days >= 14) ? ($subtotal * $discount_percent / 100) : 0;
$vat = ($subtotal - $discount) * $vat_percent / 100;
$total = $subtotal - $discount + $card_fee + $vat;

// Inserăm în baza de date
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

// Afișare rezultat
echo "<div class='text-green-600 text-2xl font-bold'>✅ Booking successful!</div>";
echo "<p class='text-gray-700'>Thank you, <span class='font-semibold'>" . htmlspecialchars($name) . "</span>! Your booking is confirmed for <span class='font-semibold'>$days</span> night(s).</p>";

echo "<div class='grid grid-cols-2 gap-4 text-sm text-gray-600 text-left mt-6'>
  <div><strong>📅 Check-in:</strong> $checkin</div>
  <div><strong>📅 Check-out:</strong> $checkout</div>
  <div><strong>👨‍👩‍👧‍👦 Guests:</strong> $adults Adults, $children Children</div>
  <div><strong>🐾 Pets:</strong> " . htmlspecialchars($pets) . "</div>
  <div><strong>💳 Card Fee:</strong> £" . number_format($card_fee, 2) . "</div>
  <div><strong>🏷️ Discount:</strong> £" . number_format($discount, 2) . "</div>
  <div><strong>🧾 VAT:</strong> £" . number_format($vat, 2) . "</div>
  <div><strong>💰 Total:</strong> £" . number_format($total, 2) . "</div>
</div>";

echo "<button onclick='window.print()' class='mt-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition'>🖨️ Print Confirmation</button>";
echo "<a href='index.php' class='ml-3 inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition'>🏠 Back to Home</a>";

echo "<script>
  confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
</script>";

echo '</div></body></html>';
?>
