<?php
require './databasse/db.php';

$hotel_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();
?>

<h2>Booking: <?= htmlspecialchars($hotel['title']) ?></h2>

<!-- ✅ Afișare toate imaginile hotelului -->
<div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
<?php
$img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
$img_stmt->bind_param("i", $hotel_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();

while ($img = $img_result->fetch_assoc()) {
    echo "<img src='" . htmlspecialchars($img['image_path']) . "' width='250' style='border:1px solid #ccc; border-radius:8px;'>";
}

$img_stmt->close();
?>
</div>

<!-- Formular rezervare -->
<form action="process_booking.php" method="POST">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">

    <label>Your Name:</label>
    <input type="text" name="name" required><br><br>

    <label>Phone:</label>
    <input type="text" name="phone" required><br><br>

    <label>Check-in Date:</label>
    <input type="date" name="checkin" required><br><br>

    <label>Check-out Date:</label>
    <input type="date" name="checkout" required><br><br>

    <label>Adults:</label>
    <input type="number" name="adults" min="1" value="1" required><br><br>

    <label>Children:</label>
    <input type="number" name="children" min="0" value="0"><br><br>

    <label>Pets:</label>
    <select name="pets">
        <option value="no">No</option>
        <option value="yes">Yes</option>
    </select><br><br>

    <button type="submit">Confirm Booking</button>
</form>
