<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Access denied!";
    exit;
}

$result = $conn->query("
    SELECT b.*, h.title 
    FROM bookings b 
    JOIN hotels h ON b.hotel_id = h.id 
    ORDER BY b.created_at DESC
");

echo "<h1>All Bookings</h1>";
echo "<table border='1' cellpadding='8'><tr>
<th>Hotel</th><th>Name</th><th>Phone</th><th>Check-in</th><th>Check-out</th><th>Days</th>
<th>Subtotal</th><th>Card Fee</th><th>Discount</th><th>VAT</th><th>Total</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['title']}</td>";
    echo "<td>{$row['customer_name']}</td>";
    echo "<td>{$row['phone']}</td>";
    echo "<td>{$row['checkin_date']}</td>";
    echo "<td>{$row['checkout_date']}</td>";
    echo "<td>{$row['days']}</td>";
    echo "<td>£{$row['subtotal']}</td>";
    echo "<td>£{$row['card_fee']}</td>";
    echo "<td>£{$row['discount']}</td>";
    echo "<td>£{$row['vat']}</td>";
    echo "<td><strong>£{$row['total']}</strong></td>";
    echo "</tr>";
}
echo "</table>";
