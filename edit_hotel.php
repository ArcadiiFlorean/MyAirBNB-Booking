<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Missing hotel ID.";
    exit;
}
$hotel_id = (int)$_GET['id'];

$user_id = $_SESSION['user_id'];

// Asigură-te că hotelul aparține utilizatorului curent
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $hotel_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Hotel not found or access denied.";
    exit;
}

$hotel = $result->fetch_assoc();
?>

<h2>Edit Hotel: <?= htmlspecialchars($hotel['title']) ?></h2>

<form action="process_edit_hotel.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">

    <label>Title:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($hotel['title']) ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?= htmlspecialchars($hotel['description']) ?></textarea><br><br>

    <label>Price per Day (£):</label><br>
    <input type="number" step="0.01" name="price" value="<?= $hotel['price_per_day'] ?>" required><br><br>

    <label>Card Fee (£):</label><br>
    <input type="number" step="0.01" name="card_fee" value="<?= $hotel['card_fee'] ?>" required><br><br>

    <label>Discount (%):</label><br>
    <input type="number" name="discount" value="<?= $hotel['discount_percentage'] ?>" required><br><br>

    <label>VAT (%):</label><br>
    <input type="number" name="vat" value="<?= $hotel['vat_percentage'] ?>" required><br><br>

   <!-- 🔁 Afișare imagini existente -->
   <h4>Current Images:</h4>
<div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
<?php
$img_stmt = $conn->prepare("SELECT id, image_path FROM hotel_images WHERE hotel_id = ?");
$img_stmt->bind_param("i", $hotel_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();

while ($img = $img_result->fetch_assoc()) {
    echo "<div style='position:relative;'>";
    echo "<img src='" . htmlspecialchars($img['image_path']) . "' width='120' style='border:1px solid #ccc; border-radius:5px;'><br>";
    echo "<a href='delete_image.php?image_id=" . $img['id'] . "&hotel_id=" . $hotel_id . "' style='color:red; font-size:12px;' onclick=\"return confirm('Are you sure you want to delete this image?')\">Delete</a>";
    echo "</div>";
}
$img_stmt->close();
?>
</div>


<!-- 🆕 Upload multiple images -->
<label>Add More Images:</label><br>
<input type="file" name="images[]" accept="image/*" multiple><br><br>

    <button type="submit">Save Changes</button>
</form>
