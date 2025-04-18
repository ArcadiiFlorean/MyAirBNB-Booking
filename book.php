<?php
require './databasse/db.php';

$hotel_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();
$disabledDates = [];
$bookingQuery = $conn->prepare("SELECT checkin_date, checkout_date FROM bookings WHERE hotel_id = ?");
$bookingQuery->bind_param("i", $hotel_id);
$bookingQuery->execute();
$bookingResult = $bookingQuery->get_result();

while ($row = $bookingResult->fetch_assoc()) {
    $start = new DateTime($row['checkin_date']);
    $end = new DateTime($row['checkout_date']);
    while ($start <= $end) {
        $disabledDates[] = $start->format('Y-m-d');
        $start->modify('+1 day');
    }
}

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
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gray-100 text-gray-800 ">
<?php include './partials/header.php'; ?>



<div class="max-w-6xl mx-auto pt-[150px]">
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
<!-- ... partea PHP de sus rămâne neschimbată ... -->

<!-- În interiorul formularului de booking -->
<form action="process_booking.php" method="POST" class="bg-white p-6 rounded-xl max-w-xl shadow-md ">
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

    <!-- ✅ Aici am adăugat ID-uri pentru Flatpickr -->
    <div>
      <label class="block mb-1 font-medium">Check-in:</label>
      <input type="date" id="checkin" name="checkin" class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>

    <div>
      <label class="block mb-1 font-medium">Check-out:</label>
      <input type="date" id="checkout" name="checkout" class="w-full border border-gray-300 rounded px-3 py-2" required>
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

  <!-- Prețul -->
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
<!-- Social Section -->
<section class="mt-16 bg-white py-1
 rounded-xl shadow-md max-w-xl mx-auto text-center">
  <h3 class="text-2xl font-bold mb-6 text-gray-800">Connect with us</h3>
  <div class="flex justify-center space-x-6 text-3xl text-gray-600">
    <a href="https://facebook.com" target="_blank" class="hover:text-blue-600 transition">
      <i class="fab fa-facebook-square"></i>
    </a>
    <a href="https://instagram.com" target="_blank" class="hover:text-pink-500 transition">
      <i class="fab fa-instagram"></i>
    </a>
    <a href="https://twitter.com" target="_blank" class="hover:text-blue-400 transition">
      <i class="fab fa-twitter"></i>
    </a>
    <a href="https://linkedin.com" target="_blank" class="hover:text-blue-700 transition">
      <i class="fab fa-linkedin"></i>
    </a>
  </div>
</section>

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
<div class="max-w-6xl mx-auto px-4 pt-10">
<h3 class="text-xl font-semibold mb-4 ">Location</h3>
  <div class="map-and-things flex flex-wrap md:flex-nowrap gap-8 justify-between items-start">

    <!-- Location Section -->
    <div class="google-maps-container w-full md:w-1/2">
     
      <div class="rounded-xl overflow-hidden shadow-md border border-gray-300">
        <iframe
          src="https://www.google.com/maps/embed?pb=..."
          width="460"
          height="400"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>

    <!-- Things to Know Section -->
    <div class="things-to-khow w-full md:w-1/2 bg-white p-6 rounded-xl shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Things to know</h2>

      <!-- House Rules -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center gap-2">
          🏡 <span>House rules</span>
        </h3>
        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
          <?= nl2br(htmlspecialchars($hotel['house_rules'] ?? 'No rules provided yet.')) ?>
        </p>
      </div>

      <!-- Safety -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center gap-2">
          🛡️ <span>Safety & property</span>
        </h3>
        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
          <?= nl2br(htmlspecialchars($hotel['safety'] ?? 'No safety info provided yet.')) ?>
        </p>
      </div>

      <!-- Cancellation -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center gap-2">
          📅 <span>Cancellation policy</span>
        </h3>
        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
          <?= nl2br(htmlspecialchars($hotel['cancellation_policy'] ?? 'No policy info provided yet.')) ?>
        </p>
      </div>
    </div>

  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

const disabledDates = <?= json_encode($disabledDates) ?>;

flatpickr("#checkin", {
  dateFormat: "Y-m-d",
  disable: disabledDates,
  minDate: "today"
});

flatpickr("#checkout", {
  dateFormat: "Y-m-d",
  disable: disabledDates,
  minDate: "today"
});


function toggleSection(id, btn) {
  const section = document.getElementById(id);
  const hiddenItems = section.querySelectorAll('.hidden');
  const isExpanded = section.classList.contains('max-h-[1000px]');

  if (!isExpanded) {
    hiddenItems.forEach(item => item.classList.remove('hidden'));
    section.classList.remove('max-h-[75px]', 'max-h-[50px]');
    section.classList.add('max-h-[1000px]');
    btn.textContent = 'Show less';
  } else {
    hiddenItems.forEach(item => item.classList.add('hidden'));
    section.classList.remove('max-h-[1000px]');
    if (id === 'houseRulesList') {
      section.classList.add('max-h-[75px]');
    } else {
      section.classList.add('max-h-[50px]');
    }
    btn.textContent = 'Show more';
  }
}
</script>
<script src="./script.js"></script>

</body>
</html>
