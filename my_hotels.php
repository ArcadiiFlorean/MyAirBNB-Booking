<?php
session_start();
require './databasse/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM hotels WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Hotels - VacayStar</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-6">My Hotels</h2>

    <?php while ($hotel = $result->fetch_assoc()): ?>
      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($hotel['title']) ?></h3>

        <div class="flex gap-3 overflow-x-auto mb-4">
          <?php
          $imgStmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
          $imgStmt->bind_param("i", $hotel['id']);
          $imgStmt->execute();
          $imgResult = $imgStmt->get_result();
          while ($img = $imgResult->fetch_assoc()): ?>
            <img src="<?= htmlspecialchars($img['image_path']) ?>" class="w-32 h-20 object-cover rounded shadow" />
          <?php endwhile; $imgStmt->close(); ?>
        </div>

        <p class="text-gray-700">£<?= $hotel['price_per_day'] ?> / night</p>
        <p class="text-gray-600"><?= htmlspecialchars($hotel['description']) ?></p>
        <p class="text-sm text-gray-500 mt-1">Max guests: <?= $hotel['max_guests'] ?></p>

        <div class="mt-4 flex gap-2">
          <a href="edit_hotel.php?id=<?= $hotel['id'] ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
          <a href="delete_hotel.php?id=<?= $hotel['id'] ?>" onclick="return confirm('Are you sure?')" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</a>
        </div>

        <h4 class="text-lg font-semibold mt-6 mb-2">Bookings</h4>
        <?php
        $bookings_stmt = $conn->prepare("SELECT * FROM bookings WHERE hotel_id = ?");
        $bookings_stmt->bind_param("i", $hotel['id']);
        $bookings_stmt->execute();
        $bookings_result = $bookings_stmt->get_result();
        ?>

        <?php if ($bookings_result->num_rows > 0): ?>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
              <thead>
                <tr class="bg-gray-200 text-sm text-gray-700">
                  <th class="px-4 py-2 border">Customer</th>
                  <th class="px-4 py-2 border">Phone</th>
                  <th class="px-4 py-2 border">Check-in</th>
                  <th class="px-4 py-2 border">Check-out</th>
                  <th class="px-4 py-2 border">Days</th>
                  <th class="px-4 py-2 border">Adults</th>
                  <th class="px-4 py-2 border">Children</th>
                  <th class="px-4 py-2 border">Pets</th>
                  <th class="px-4 py-2 border">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                <tr class="text-sm text-gray-800">
                  <td class="px-4 py-2 border"><?= htmlspecialchars($booking['customer_name']) ?></td>
                  <td class="px-4 py-2 border"><?= htmlspecialchars($booking['phone']) ?></td>
                  <td class="px-4 py-2 border"><?= $booking['checkin_date'] ?></td>
                  <td class="px-4 py-2 border"><?= $booking['checkout_date'] ?></td>
                  <td class="px-4 py-2 border"><?= $booking['days'] ?></td>
                  <td class="px-4 py-2 border"><?= $booking['adults'] ?></td>
                  <td class="px-4 py-2 border"><?= $booking['children'] ?></td>
                  <td class="px-4 py-2 border"><?= ucfirst($booking['pets']) ?></td>
                  <td class="px-4 py-2 border font-semibold">£<?= $booking['total'] ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-500 text-sm">No bookings yet.</p>
        <?php endif; $bookings_stmt->close(); ?>
      </div>
    <?php endwhile; ?>

    <?php if (isset($_GET['edited']) && $_GET['edited'] === 'success'): ?>
      <p class="text-green-600 font-semibold mb-4">Hotel updated successfully!</p>
    <?php endif; ?>

    <!-- Form add hotel -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h3 class="text-xl font-semibold mb-4">Add New Hotel</h3>
      <form action="process_add_hotel.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="text" name="title" placeholder="Title" required class="w-full border px-3 py-2 rounded">
        <textarea name="description" placeholder="Description" required class="w-full border px-3 py-2 rounded"></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price per Day (£)" required class="w-full border px-3 py-2 rounded">
        <input type="number" step="0.01" name="card_fee" placeholder="Card Fee (£)" required class="w-full border px-3 py-2 rounded">
        <input type="number" name="discount" placeholder="Discount %" required class="w-full border px-3 py-2 rounded">
        <input type="number" name="vat" value="15" placeholder="VAT %" required class="w-full border px-3 py-2 rounded">
        <input type="number" name="max_guests" min="1" placeholder="Max Guests" required class="w-full border px-3 py-2 rounded">
        <input type="file" name="images[]" accept="image/*" multiple required class="w-full">
        <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">Add Hotel</button>
      </form>
    </div>

    <div class="mt-8">
      <a href="registerPHP/logout.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Logout</a>
    </div>
  </div>
</body>
</html>