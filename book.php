<?php
require './databasse/db.php';

$hotel_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking: <?= htmlspecialchars($hotel['title']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">

<div class="max-w-6xl mx-auto">
  <h2 class="text-3xl font-bold text-center mb-8">Booking: <?= htmlspecialchars($hotel['title']) ?></h2>

  <!-- Imagini Hotel -->
  <?php
  $img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
  $img_stmt->bind_param("i", $hotel_id);
  $img_stmt->execute();
  $img_result = $img_stmt->get_result();
  $images = [];
  while ($img = $img_result->fetch_assoc()) {
    $images[] = htmlspecialchars($img['image_path']);
  }
  $img_stmt->close();
  ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <!-- Imagine mare -->
    <div>
      <?php if (!empty($images)): ?>
        <img src="<?= $images[0] ?>" class="w-full h-full object-cover rounded-xl shadow-lg hover:scale-105 transition duration-200 cursor-pointer" alt="Main Image">
      <?php endif; ?>
    </div>

    <!-- 4 imagini mici -->
   <!-- Containerul pozelor mici -->
<div class="relative grid grid-cols-2 grid-rows-2 gap-2">

<!-- Buton peste imagini -->
<button onclick="openFullGallery()" 
        class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-sm px-3 py-1 rounded hover:bg-opacity-70 z-10 ">
  View All Photos
</button>

<?php foreach (array_slice($images, 1, 4) as $img): ?>
  <img src="<?= $img ?>" alt="Small Image"
       class="w-full h-[200px] object-cover rounded shadow hover:scale-105 transition duration-200 cursor-pointer ">
<?php endforeach; ?>

</div>


  </div>

</button>
  <!-- Formular rezervare -->
  <form action="process_booking.php" method="POST" class="bg-white p-6 rounded-xl shadow-md mb-10">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">

    <h3 class="text-xl font-semibold mb-4">Complete your booking</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
      <div>
        <label class="block mb-1 font-medium">Your Name:</label>
        <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Phone:</label>
        <input type="text" name="phone" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Check-in:</label>
        <input type="date" name="checkin" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Check-out:</label>
        <input type="date" name="checkout" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Adults:</label>
        <input type="number" name="adults" min="1" value="1" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Children:</label>
        <input type="number" name="children" min="0" value="0" class="w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <div>
        <label class="block mb-1 font-medium">Pets:</label>
        <select name="pets" class="w-full border border-gray-300 rounded px-3 py-2">
          <option value="no">No</option>
          <option value="yes">Yes</option>
        </select>
      </div>
    </div>

    <button type="submit" class="mt-4 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
      Confirm Booking
    </button>
  </form>

  <!-- Formular rating -->
  <form action="submit_rating.php" method="POST" class="bg-white p-6 rounded-xl shadow-md">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
    <h3 class="text-xl font-semibold mb-4">Leave a Rating</h3>

    <label class="block mb-2 font-medium">Your Rating:</label>
    <select name="rating" class="w-full border border-gray-300 rounded px-3 py-2 mb-4" required>
      <option value="">Choose...</option>
      <option value="5">⭐⭐⭐⭐⭐</option>
      <option value="4">⭐⭐⭐⭐</option>
      <option value="3">⭐⭐⭐</option>
      <option value="2">⭐⭐</option>
      <option value="1">⭐</option>
    </select>

    <label class="block mb-1 font-medium">Comment (optional):</label>
    <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 mb-4"></textarea>

    <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
      Submit Rating
    </button>
  </form>

  <div id="fullGalleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 p-8 overflow-y-auto hidden">
  <button onclick="closeFullGallery()" class="text-white text-3xl absolute top-4 right-6">&times;</button>
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-10">
    <?php foreach ($images as $img): ?>
      <img src="<?= $img ?>" class="w-full h-60 object-cover rounded-lg shadow" alt="Gallery Image">
    <?php endforeach; ?>
  </div>
</div>
</div>
<script src="./script.js"></script>
</body>
</html>
