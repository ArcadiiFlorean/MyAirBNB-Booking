<?php
require './databasse/db.php';

$hotel_id = $_POST['hotel_id'];
$house_rules = $_POST['house_rules'];
$safety = $_POST['safety'];
$cancellation_policy = $_POST['cancellation_policy'];

$stmt = $conn->prepare("UPDATE hotels SET house_rules = ?, safety = ?, cancellation_policy = ? WHERE id = ?");
$stmt->bind_param("sssi", $house_rules, $safety, $cancellation_policy, $hotel_id);

if ($stmt->execute()) {
    header("Location: my_hotels.php?policies_updated=success");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
