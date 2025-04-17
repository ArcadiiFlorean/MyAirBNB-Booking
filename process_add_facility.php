<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

$hotel_id = $_POST['hotel_id'];
$facility = trim($_POST['facility_name']);
$icon = trim($_POST['icon']);

if ($hotel_id && $facility && $icon) {
    $stmt = $conn->prepare("INSERT INTO hotel_facilities (hotel_id, facility_name, icon) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $hotel_id, $facility, $icon);
    $stmt->execute();
    $stmt->close();
}

header("Location: my_hotels.php");
exit;
