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

  <!-- Existing facilities -->
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

  <!-- Add new facility -->
  <form action="process_add_facility.php" method="POST" class="flex gap-2 items-center">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
    <input type="text" name="facility_name" placeholder="Facility name (e.g. Wifi)" required class="border px-3 py-1 rounded w-1/2">
    <input type="text" name="icon" placeholder="Emoji (e.g. 📶)" required class="border px-3 py-1 rounded w-20 text-center">
    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Add</button>
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
    <div class="bg-white p-6 rounded-lg shadow mb-8">
  <h3 class="text-xl font-semibold mb-4">Update Host Profile</h3>
  <form action="update_host_profile.php" method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="text" name="host_name" placeholder="Hosted by..." value="<?= htmlspecialchars($hostProfile['host_name'] ?? '') ?>" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="experience_years" placeholder="Years hosting" value="<?= $hostProfile['experience_years'] ?? '' ?>" required class="w-full border px-3 py-2 rounded">
    <textarea name="highlights" placeholder="Listing Highlights" required class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($hostProfile['highlights'] ?? '') ?></textarea>
    <input type="file" name="profile_image" accept="image/*" class="w-full">
    <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded hover:bg-purple-700">Save Profile</button>
  </form>
</div>
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
      <!-- Facilități hotel (checkbox-uri) -->
<div>
  <label><input type="checkbox" name="facilities[]" value="🛏️|Double bed"> 🛏️ Double bed</label><br>
  <label><input type="checkbox" name="facilities[]" value="📶|Wi-Fi"> 📶 Wi-Fi</label><br>
  <label><input type="checkbox" name="facilities[]" value="🚿|Private Bathroom"> 🚿 Private Bathroom</label><br>
  <label><input type="checkbox" name="facilities[]" value="📺|Smart TV"> 📺 Smart TV</label><br>
  <label><input type="checkbox" name="facilities[]" value="☕|Coffee Maker"> ☕ Coffee Maker</label><br>
  <label><input type="checkbox" name="facilities[]" value="🅿️|Free Parking"> 🅿️ Free Parking</label><br>
  <label><input type="checkbox" name="facilities[]" value="❄️|Air Conditioning"> ❄️ Air Conditioning</label><br>
  <label><input type="checkbox" name="facilities[]" value="🐶|Pet Friendly"> 🐶 Pet Friendly</label><br>
</div>

    </div>

    <div class="mt-8">
      <a href="registerPHP/logout.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Logout</a>
    </div>
  </div>
</body>
</html>