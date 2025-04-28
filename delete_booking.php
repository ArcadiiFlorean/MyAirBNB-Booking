<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    // Verificăm dacă booking-ul aparține hotelului userului
    $stmt = $conn->prepare("
        DELETE b
        FROM bookings b
        JOIN hotels h ON b.hotel_id = h.id
        WHERE b.id = ? AND h.user_id = ?
    ");
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: my_hotels.php?msg=Booking+deleted");
        exit;
    } else {
        echo "Booking not found or you don't have permission.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
