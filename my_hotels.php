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
<?php include './partials/header.php'; ?>
  <div class="max-w-6xl mx-auto pt-[100px] ">
    <h2 class="text-3xl font-bold mb-6">My Hotels</h2>
    <div class="flex flex-row items-start justify-center space-x-10">

<!-- Form update host profile -->
<div class="bg-white p-6 rounded-lg shadow mb-8 w-full max-w-md h-[500px]">

  <h3 class="text-xl font-semibold mb-4">Update Host Profile</h3>
  <form action="update_host_profile.php" method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="text" name="host_name" placeholder="Hosted by..." value="<?= htmlspecialchars($hostProfile['host_name'] ?? '') ?>" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="experience_years" placeholder="Years hosting" value="<?= $hostProfile['experience_years'] ?? '' ?>" required class="w-full border px-3 py-2 rounded">
    <textarea name="highlights" placeholder="Listing Highlights" required class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($hostProfile['highlights'] ?? '') ?></textarea>
    <input type="file" name="profile_image" accept="image/*" class="w-full">
    <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded hover:bg-purple-700">Save Profile</button>
  </form>
</div>

<!-- Form add hotel -->
<div class="bg-white p-6 rounded-lg shadow mb-8 w-full max-w-md">
  <h3 class="text-xl font-semibold mb-4">Add New Hotel</h3>
  <form action="process_add_hotel.php" method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="text" name="title" placeholder="Title" required class="w-full border px-3 py-2 rounded">
    <textarea name="description" placeholder="Description" required class="w-full border px-3 py-2 rounded"></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price per Day (£)" required class="w-full border px-3 py-2 rounded">
    <input type="number" step="0.01" name="card_fee" placeholder="Card Fee (£)" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="discount" placeholder="Discount %" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="vat" value="15" placeholder="VAT %" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="max_guests" min="1" placeholder="Max Guests" required class="w-full border px-3 py-2 rounded">

    <!-- Facilities -->
    <div>
      <label class="block mb-2 font-medium">Facilities:</label>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm text-gray-700">
        <label><input type="checkbox" name="facilities[]" value="🛏️|Double bed"> 🛏️ Double bed</label>
        <label><input type="checkbox" name="facilities[]" value="📶|Wi-Fi"> 📶 Wi-Fi</label>
        <label><input type="checkbox" name="facilities[]" value="🚿|Private Bathroom"> 🚿 Private Bathroom</label>
        <label><input type="checkbox" name="facilities[]" value="📺|Smart TV"> 📺 Smart TV</label>
        <label><input type="checkbox" name="facilities[]" value="☕|Coffee Maker"> ☕ Coffee Maker</label>
        <label><input type="checkbox" name="facilities[]" value="🅿️|Free Parking"> 🅿️ Free Parking</label>
        <label><input type="checkbox" name="facilities[]" value="❄️|Air Conditioning"> ❄️ Air Conditioning</label>
        <label><input type="checkbox" name="facilities[]" value="🐶|Pet Friendly"> 🐶 Pet Friendly</label>
      </div>
    </div>

    <input type="file" name="images[]" accept="image/*" multiple required class="w-full">
    <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">Add Hotel</button>
  </form>
</div>



</div> <!-- închidem container hoteluri -->
<!-- Container pentru toate hotelurile -->
<div class="flex flex-wrap justify-center gap-6">

<?php while ($hotel = $result->fetch_assoc()): ?>
  <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">

    <!-- Titlu hotel -->
    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($hotel['title']) ?></h3>

    <!-- Galerie imagini hotel -->
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

    <!-- Preț și descriere -->
    <p class="text-gray-700">£<?= $hotel['price_per_day'] ?> / night</p>
    <p class="text-gray-600"><?= htmlspecialchars($hotel['description']) ?></p>
    <p class="text-sm text-gray-500 mt-1">Max guests: <?= $hotel['max_guests'] ?></p>

    <!-- Butoane Edit / Delete -->
    <div class="mt-4 flex gap-2">
      <a href="edit_hotel.php?id=<?= $hotel['id'] ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
      <a href="delete_hotel.php?id=<?= $hotel['id'] ?>" onclick="return confirm('Are you sure?')" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</a>
    </div>

    <!-- Facilities -->
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

    <!-- Policies -->
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

    <!-- Bookings -->
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

  </div> <!-- închidem card hotel -->

<?php endwhile; ?>



</body>
</html>
