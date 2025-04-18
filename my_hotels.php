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

$hostStmt = $conn->prepare("SELECT * FROM host_profiles WHERE user_id = ?");
$hostStmt->bind_param("i", $user_id);
$hostStmt->execute();
$hostProfile = $hostStmt->get_result()->fetch_assoc();
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

        <!-- Facilities Section -->
        <div class="mt-6">
          <h4 class="text-lg font-semibold mb-2">Facilities</h4>
          <?php
          $facStmt = $conn->prepare("SELECT facility_name, icon FROM hotel_facilities WHERE hotel_id = ?");
          $facStmt->bind_param("i", $hotel['id']);
          $facStmt->execute();
          $facResult = $facStmt->get_result();
          if ($facResult->num_rows > 0): ?>
            <ul class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm text-gray-700 mb-4">
              <?php while ($fac = $facResult->fetch_assoc()): ?>
                <li><?= $fac['icon'] ?> <?= htmlspecialchars($fac['facility_name']) ?></li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-sm text-gray-500 mb-2">No facilities added yet.</p>
          <?php endif; ?>
          <?php $facStmt->close(); ?>
        </div>

        <!-- Policies Section -->
        <div class="mt-6 bg-gray-50 p-4 rounded">
          <h4 class="text-lg font-semibold mb-3">Things to Know</h4>
          <form action="update_hotel_policies.php" method="POST" class="space-y-3">
            <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">

            <div>
              <label class="block text-sm font-medium">House Rules:</label>
              <textarea name="house_rules" class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($hotel['house_rules'] ?? '') ?></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium">Safety & Property:</label>
              <textarea name="safety" class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($hotel['safety'] ?? '') ?></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium">Cancellation Policy:</label>
              <textarea name="cancellation_policy" class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($hotel['cancellation_policy'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Policies</button>
          </form>
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
  </div>
</body>
</html>
