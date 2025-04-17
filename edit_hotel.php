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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">

<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-md">
  <h2 class="text-2xl font-bold mb-6 text-center">Edit Hotel: <?= htmlspecialchars($hotel['title']) ?></h2>

  <form action="process_edit_hotel.php" method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">

    <div>
      <label class="block font-medium mb-1">Title:</label>
      <input type="text" name="title" value="<?= htmlspecialchars($hotel['title']) ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>

    <div>
      <label class="block font-medium mb-1">Description:</label>
      <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2" required><?= htmlspecialchars($hotel['description']) ?></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block font-medium mb-1">Price per Day (£):</label>
        <input type="number" step="0.01" name="price" value="<?= $hotel['price_per_day'] ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Card Fee (£):</label>
        <input type="number" step="0.01" name="card_fee" value="<?= $hotel['card_fee'] ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="block font-medium mb-1">Discount (%):</label>
        <input type="number" name="discount" value="<?= $hotel['discount_percentage'] ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">VAT (%):</label>
        <input type="number" name="vat" value="<?= $hotel['vat_percentage'] ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Max Guests:</label>
        <input type="number" name="max_guests" value="<?= $hotel['max_guests'] ?>" class="w-full border border-gray-300 rounded px-3 py-2" min="1" required>
      </div>
    </div>

    <div>
      <h4 class="text-lg font-semibold mt-6 mb-2">Current Images:</h4>
      <div class="flex flex-wrap gap-3">
        <?php
        $img_stmt = $conn->prepare("SELECT id, image_path FROM hotel_images WHERE hotel_id = ?");
        $img_stmt->bind_param("i", $hotel_id);
        $img_stmt->execute();
        $img_result = $img_stmt->get_result();
        while ($img = $img_result->fetch_assoc()):
        ?>
          <div class="relative">
            <img src="<?= htmlspecialchars($img['image_path']) ?>" class="w-28 h-20 object-cover rounded shadow border">
            <a href="delete_image.php?image_id=<?= $img['id'] ?>&hotel_id=<?= $hotel_id ?>" 
               class="absolute top-1 right-1 text-xs text-red-600 bg-white px-1 rounded hover:underline"
               onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
          </div>
        <?php endwhile; $img_stmt->close(); ?>
      </div>
    </div>

    <div>
      <label class="block font-medium mb-1">Add More Images:</label>
      <input type="file" name="images[]" accept="image/*" multiple class="w-full">
    </div>

    <div class="text-center">
      <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Save Changes</button>
    </div>
  </form>
</div>

</body>
</html>
