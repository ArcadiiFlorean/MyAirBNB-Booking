<?php
require './databasse/db.php';

$hotel_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();

// ✅ Adăugat: verificăm dacă hotelul a fost găsit
if (!$hotel) {
    echo "<div style='padding: 2rem; font-family: sans-serif; color: red;'>❌ Hotel not found. Please go back and try again.</div>";
    exit;
}

$pricePerNight = $hotel['price_per_day'] ?? 0;

// Fetch host profile data
$hostStmt = $conn->prepare("SELECT hp.host_name, hp.experience_years, hp.highlights, hp.profile_image 
                            FROM host_profiles hp 
                            JOIN hotels h ON hp.user_id = h.user_id 
                            WHERE h.id = ?");
$hostStmt->bind_param("i", $hotel_id);
$hostStmt->execute();
$hostData = $hostStmt->get_result()->fetch_assoc();
$hostStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking: <?= htmlspecialchars($hotel['title']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./block-css/book.css">
</head>
<body class="bg-gray-100 text-gray-800 p-6">
<div class="max-w-6xl mx-auto">
  <h2 class="text-3xl font-bold text-center mb-8">Booking: <?= htmlspecialchars($hotel['title']) ?></h2>

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
    <div>
      <?php if (!empty($images)): ?>
        <img src="<?= $images[0] ?>" class="w-full h-[400px] object-cover rounded-xl shadow-lg hover:scale-105 transition duration-200 cursor-pointer" onclick='openSingleImageModal(<?= json_encode($images) ?>, 0)'>
      <?php endif; ?>
    </div>

    <div class="relative grid grid-cols-2 grid-rows-2 gap-2">
      <button onclick="openPhotoModal(<?= json_encode($images) ?>)" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-sm px-3 py-1 rounded hover:bg-opacity-70 z-10">
        View All Photos
      </button>

      <?php foreach (array_slice($images, 1, 4) as $i => $img): ?>
        <img src="<?= $img ?>" class="w-full h-[200px] object-cover rounded shadow hover:scale-105 transition duration-200 cursor-pointer" onclick='openSingleImageModal(<?= json_encode($images) ?>, <?= $i + 1 ?>)'>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Booking Form -->
   <div class="booking-form-container">
  <form action="process_booking.php" method="POST" class="bg-white p-6 rounded-xl max-w-xl shadow-md mb-10">
    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
    <h3 class="text-xl font-semibold mb-4">Complete your booking</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
      <div><label class="block mb-1 font-medium">Your Name:</label><input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" required></div>
      <div><label class="block mb-1 font-medium">Phone:</label><input type="text" name="phone" class="w-full border border-gray-300 rounded px-3 py-2" required></div>
      <div><label class="block mb-1 font-medium">Check-in:</label><input type="date" name="checkin" class="w-full border border-gray-300 rounded px-3 py-2" required></div>
      <div><label class="block mb-1 font-medium">Check-out:</label><input type="date" name="checkout" class="w-full border border-gray-300 rounded px-3 py-2" required></div>
      <div><label class="block mb-1 font-medium">Adults:</label><input type="number" name="adults" min="1" value="1" class="w-full border border-gray-300 rounded px-3 py-2" required></div>
      <div><label class="block mb-1 font-medium">Children:</label><input type="number" name="children" min="0" value="0" class="w-full border border-gray-300 rounded px-3 py-2"></div>
      <div><label class="block mb-1 font-medium">Pets:</label>
        <select name="pets" class="w-full border border-gray-300 rounded px-3 py-2">
          <option value="no">No</option>
          <option value="yes">Yes</option>
        </select>
      </div>
    </div>

    <div class="mb-4">
      <p class="text-sm text-gray-700"><strong>Price per Night:</strong> £<?= number_format($pricePerNight, 2) ?></p>
      <p class="text-sm text-gray-700"><strong>Nights:</strong> <span id="nightsCount">0</span></p>
      <p class="text-sm text-gray-700"><strong>Total:</strong> <span id="totalDisplay">£0.00</span></p>
    </div>

    <button type="submit" class="mt-4 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">Confirm Booking</button>
  </form>
<div class="profile">
 <!-- Host Profile Dynamic Section -->
  <div class="bg-white p-6 rounded-xl max-w-xl shadow-md mb-10">
    <div class="flex items-center gap-4 mb-4">
      <img src="<?= !empty($hostData['profile_image']) ? $hostData['profile_image'] : './images/default-host.jpg' ?>" alt="Host Profile" class="w-16 h-16 rounded-full object-cover border border-gray-300">
      <div>
        <p class="text-lg font-semibold">Hosted by <?= htmlspecialchars($hostData['host_name'] ?? 'Unknown Host') ?></p>
        <p class="text-sm text-gray-600">🌟 Superhost · <?= htmlspecialchars($hostData['experience_years'] ?? '0') ?> years hosting</p>
      </div>
    </div>

    <h4 class="text-md font-semibold text-gray-800 mb-2">Listing Highlights</h4>
    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
      <?php foreach (explode("\n", $hostData['highlights'] ?? '') as $line): ?>
        <?php if (trim($line)): ?>
          <li><?= htmlspecialchars(trim($line)) ?></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
  <!-- What this place offers -->


<?php
$facStmt = $conn->prepare("SELECT facility_name, icon FROM hotel_facilities WHERE hotel_id = ?");
$facStmt->bind_param("i", $hotel_id);
$facStmt->execute();
$facResult = $facStmt->get_result();
?>

<?php if ($facResult->num_rows > 0): ?>
  <div class="bg-white p-6 rounded-xl shadow-md max-w-xl mb-10">
    <h3 class="text-xl font-semibold mb-4">What this place offers</h3>
    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-700">
      <?php while ($fac = $facResult->fetch_assoc()): ?>
        <li><?= htmlspecialchars($fac['icon']) ?> <?= htmlspecialchars($fac['facility_name']) ?></li>
      <?php endwhile; ?>
    </ul>
  </div>
<?php endif; ?>
<?php $facStmt->close(); ?>

</div>
 
  </div>

  <!-- Modals & Scripts -->
  <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-y-auto">
    <button onclick="closePhotoModal()" class="absolute top-4 right-6 text-white text-3xl hover:text-red-500 z-50">&times;</button>
    <div id="photoGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-6 mt-12"></div>
  </div>

  <div id="singleImageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center hidden">
    <button onclick="closeSingleImageModal()" class="absolute top-4 right-6 text-white text-3xl font-bold hover:text-red-400 z-50">&times;</button>
    <button onclick="prevSingleImage()" class="absolute left-4 text-white text-4xl font-bold z-50 hover:text-blue-400">&#8592;</button>
    <img id="singleModalImage" src="" class="max-w-[90%] max-h-[90%] rounded shadow-lg" alt="Modal Image" />
    <button onclick="nextSingleImage()" class="absolute right-4 text-white text-4xl font-bold z-50 hover:text-blue-400">&#8594;</button>
  </div>
</div>


<script>
let imageList = [];
let currentImgIndex = 0;

function openPhotoModal(images) {
  const modal = document.getElementById('photoModal');
  const grid = document.getElementById('photoGrid');
  grid.innerHTML = '';
  images.forEach(src => {
    const img = document.createElement('img');
    img.src = src;
    img.className = 'w-full h-60 object-cover rounded-lg shadow hover:scale-105 transition duration-200';
    grid.appendChild(img);
  });
  modal.classList.remove('hidden');
}

function closePhotoModal() {
  document.getElementById('photoModal').classList.add('hidden');
}

function openSingleImageModal(srcArray, index) {
  imageList = srcArray;
  currentImgIndex = index;
  document.getElementById('singleModalImage').src = imageList[currentImgIndex];
  document.getElementById('singleImageModal').classList.remove('hidden');
}

function closeSingleImageModal() {
  document.getElementById('singleImageModal').classList.add('hidden');
}

function nextSingleImage() {
  currentImgIndex = (currentImgIndex + 1) % imageList.length;
  document.getElementById('singleModalImage').src = imageList[currentImgIndex];
}

function prevSingleImage() {
  currentImgIndex = (currentImgIndex - 1 + imageList.length) % imageList.length;
  document.getElementById('singleModalImage').src = imageList[currentImgIndex];
}

document.addEventListener('DOMContentLoaded', function () {
  const checkinInput = document.querySelector('input[name="checkin"]');
  const checkoutInput = document.querySelector('input[name="checkout"]');
  const totalDisplay = document.getElementById('totalDisplay');
  const pricePerNight = <?= $pricePerNight ?>;

  function updateTotal() {
    const checkin = new Date(checkinInput.value);
    const checkout = new Date(checkoutInput.value);

    if (checkin && checkout && checkout > checkin) {
      const diffTime = checkout - checkin;
      const nights = diffTime / (1000 * 60 * 60 * 24);
      const total = nights * pricePerNight;

      document.getElementById('nightsCount').textContent = nights;
      totalDisplay.textContent = `£${total.toFixed(2)}`;
    } else {
      document.getElementById('nightsCount').textContent = 0;
      totalDisplay.textContent = '£0.00';
    }
  }

  checkinInput.addEventListener('change', updateTotal);
  checkoutInput.addEventListener('change', updateTotal);
});
</script>
<script src="./script.js"></script>
</body>
</html>
