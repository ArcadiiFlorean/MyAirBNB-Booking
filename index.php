<?php
session_start();
require './databasse/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>VacayStar</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./block-css/index.css" />
  <link rel="stylesheet" href="./general.css/settings.css" />
</head>
<body class="bg-gray-50 text-gray-800">

<?php include './partials/header.php'; ?>

<h1 class="text-3xl font-bold text-center mt-6 mb-4">Available Hotels</h1>

<div class="container mx-auto px-4">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php
    $result = $conn->query("SELECT * FROM hotels");

    while ($row = $result->fetch_assoc()):
      $hotel_id = $row['id'];

      // Imagini hotel
      $img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
      $img_stmt->bind_param("i", $hotel_id);
      $img_stmt->execute();
      $img_result = $img_stmt->get_result();
      $images = [];
      while ($img = $img_result->fetch_assoc()) {
        $images[] = htmlspecialchars($img['image_path']);
      }
      $img_stmt->close();

      // Rating hotel
      $rating_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE hotel_id = ?");
      $rating_stmt->bind_param("i", $hotel_id);
      $rating_stmt->execute();
      $rating_result = $rating_stmt->get_result();
      $avg_rating = round($rating_result->fetch_assoc()['avg_rating'] ?? 0, 1);
      $rating_stmt->close();
    ?>
<div class="hotel-card  relative bg-white text-center rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in overflow-hidden">
  
  <!-- Titlu hotel -->
  <h2 class="text-xl font-bold text-gray-800 mb-3 p-4"><?= htmlspecialchars($row['title']) ?></h2>

  <!-- Galerie imagini full-width -->
  <div class="relative mb-4">
    <!-- Buton stânga -->
    <button onclick="scrollLeft('slider-<?= $hotel_id ?>')" 
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-transparen border border-gray-300 rounded-full p-2 shadow hover:bg-gray-100 z-[20]">◀</button>

    <!-- Slider imagini -->
    <div id="slider-<?= $hotel_id ?>" 
     class="flex overflow-x-auto snap-x snap-mandatory gap-2 scroll-smooth relative z-0 w-full h-60">

      <?php foreach ($images as $index => $imgPath): ?>
        <img src="<?= $imgPath ?>"
     onclick='openModal(<?= json_encode($images) ?>, <?= $index ?>)'
     class="w-full h-full object-cover flex-shrink-0 snap-center cursor-pointer"
     alt="Hotel Image">

      <?php endforeach; ?>
    </div>

    <!-- Buton dreapta -->
    <button onclick="scrollRight('slider-<?= $hotel_id ?>')" 
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border border-gray-300  rounded-full p-2 shadow hover:bg-gray-100 z-[20]">▶</button>
  </div>

  <!-- Rating -->
  <div class="flex items-center justify-center mb-2">
    <span class="text-yellow-500 text-lg">★</span>
    <span class="ml-1 text-gray-700"><?= $avg_rating ?> / 5</span>
  </div>

  <!-- Info -->
  <div class="px-4 pb-4">
    <p class="text-md font-semibold text-gray-700">£<?= $row['price_per_day'] ?> / night</p>
    <p class="text-gray-600 mb-2"><?= htmlspecialchars($row['description']) ?></p>
    <a href="book.php?id=<?= $hotel_id ?>"
    target="_blank"   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
      Book Now
    </a>
  </div>
</div>

    <?php endwhile; ?>
  </div>
</div>

<!-- Modal imagine -->
<!-- <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
  <button onclick="closeModal()" class="absolute top-4 right-6 text-white text-3xl font-bold z-10 hover:text-red-400">&times;</button>
  <button onclick="prevImage()" class="absolute left-4 text-white text-3xl font-bold z-10 hover:text-blue-400">&larr;</button>

  <img id="modalImage" src="" alt="Enlarged" class="max-w-full max-h-[90vh] rounded shadow-xl z-20" />

  <button onclick="nextImage()" class="absolute right-4 text-white text-3xl font-bold z-10 hover:text-blue-400">&rarr;</button>
</div> -->

<script src="./script.js"></script>



</body>
</html>
