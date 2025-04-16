<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Obține hotelurile gazdei
$stmt = $conn->prepare("SELECT * FROM hotels WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);

$stmt->execute();
$result = $stmt->get_result();
?>

<h2>My Hotels</h2>

<!-- Afișare hoteluri + rezervările pentru fiecare -->
<?php while ($hotel = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <h3><?= htmlspecialchars($hotel['title']) ?></h3>
        <?php
$imgStmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
$imgStmt->bind_param("i", $hotel['id']);
$imgStmt->execute();
$imgResult = $imgStmt->get_result();

while ($img = $imgResult->fetch_assoc()) {
    echo '<img src="' . htmlspecialchars($img['image_path']) . '" width="200" style="margin-right:10px;">';
}

$imgStmt->close();
?>

        <p><strong>£<?= $hotel['price_per_day'] ?> / night</strong></p>
        <p><?= htmlspecialchars($hotel['description']) ?></p>
        <a href="edit_hotel.php?id=<?= $hotel['id'] ?>" style="background:#3498db; color:#fff; padding:6px 12px; text-decoration:none; border-radius:5px;">
          Edit Hotel
        </a>

        <!-- Buton Edit -->


<!-- Buton Delete -->
<a href="delete_hotel.php?id=<?= $hotel['id'] ?>" onclick="return confirm('Are you sure you want to delete this hotel?')" 
   style="background:#e74c3c; color:#fff; padding:6px 12px; text-decoration:none; border-radius:5px; margin-left:10px;">
  Delete
</a>

        <!-- ✅ Rezervări pentru acest hotel -->
        <h4>Bookings for this hotel:</h4>
        <?php
        $bookings_stmt = $conn->prepare("SELECT * FROM bookings WHERE hotel_id = ?");
        $bookings_stmt->bind_param("i", $hotel['id']);
        $bookings_stmt->execute();
        $bookings_result = $bookings_stmt->get_result();

        if ($bookings_result->num_rows > 0): ?>
          <table border="1" cellpadding="5" style="margin-top:10px;">
    <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Days</th>
        <th>Adults</th>
        <th>Children</th>
        <th>Pets</th>
        <th>Total (£)</th>
    </tr>
    <?php while ($booking = $bookings_result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($booking['customer_name']) ?></td>
            <td><?= htmlspecialchars($booking['phone']) ?></td>
            <td><?= $booking['checkin_date'] ?></td>
            <td><?= $booking['checkout_date'] ?></td>
            <td><?= $booking['days'] ?></td>
            <td><?= $booking['adults'] ?></td>
            <td><?= $booking['children'] ?></td>
            <td><?= ucfirst($booking['pets']) ?></td>
            <td><strong>£<?= $booking['total'] ?></strong></td>
        </tr>
    <?php endwhile; ?>
</table>

        <?php else: ?>
            <p>No bookings yet.</p>
        <?php endif;

        $bookings_stmt->close();
        ?>
    </div>
<?php endwhile; ?>

<hr>



<?php if (isset($_GET['edited']) && $_GET['edited'] === 'success'): ?>
    <p style="color:green;">Hotel updated successfully!</p>
<?php endif; ?>

<!-- Form pentru adăugare hotel -->
<h3>Add New Hotel</h3>
<form action="process_add_hotel.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <input type="number" step="0.01" name="price" placeholder="Price per Day (£)" required><br><br>
    <input type="number" step="0.01" name="card_fee" placeholder="Card Fee (£)" required><br><br>
    <input type="number" name="discount" placeholder="Discount %" required><br><br>
    <input type="number" name="vat" value="15" placeholder="VAT %" required><br><br>
    <input type="file" name="images[]" accept="image/*" multiple required><br><br>

    <button type="submit">Add Hotel</button>
</form>

<a href="registerPHP/logout.php" class="btn-back" style="padding:10px 20px; background:#f00; color:white; text-decoration:none; border-radius:5px;">
  Logout
</a>
