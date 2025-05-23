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
  <link rel="stylesheet" href="./block-css/header.css">
  <style>
    ::-webkit-scrollbar {
      height: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
      background: #bbb;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #888;
    }
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
  </style>
</head>
<body class="relative min-h-screen text-gray-800 bg-gradient-to-br from-white via-blue-100 to-blue-200">
  <div class="relative z-10">

<?php include './partials/header.php'; ?>

<section class="pt-[120px] pb-16 px-4">
  <h1 class="text-4xl md:text-5xl font-extrabold text-center text-blue-900 drop-shadow-md mb-10 tracking-tight">
    ✨ Discover Your Perfect Stay ✨
  </h1>

  <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    <?php
    $result = $conn->query("SELECT * FROM hotels");

    while ($row = $result->fetch_assoc()):
      $hotel_id = $row['id'];

      $img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
      $img_stmt->bind_param("i", $hotel_id);
      $img_stmt->execute();
      $img_result = $img_stmt->get_result();
      $images = [];
      while ($img = $img_result->fetch_assoc()) {
        $images[] = htmlspecialchars($img['image_path']);
      }
      $img_stmt->close();

      $rating_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE hotel_id = ?");
      $rating_stmt->bind_param("i", $hotel_id);
      $rating_stmt->execute();
      $rating_result = $rating_stmt->get_result();
      $avg_rating = round($rating_result->fetch_assoc()['avg_rating'] ?? 0, 1);
      $rating_stmt->close();
    ?>
    <div class="bg-white/30 backdrop-blur-lg rounded-2xl overflow-hidden shadow-xl border border-white/40 transform hover:scale-[1.02] transition duration-300">
      <div class="relative group h-56 overflow-hidden">
        <div id="slider-<?= $hotel_id ?>" class="flex overflow-x-auto scrollbar-hide snap-x snap-mandatory scroll-smooth h-full w-full cursor-pointer">
          <?php foreach ($images as $index => $imgPath): ?>
            <img src="<?= $imgPath ?>" onclick='openModal(<?= json_encode($images) ?>, <?= $index ?>)' alt="Hotel Image" class="object-cover w-full h-full snap-center flex-shrink-0 hover:opacity-90 transition duration-300">
          <?php endforeach; ?>
        </div>
        <button onclick="scrollLeft('slider-<?= $hotel_id ?>')" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/70 p-2 rounded-full text-gray-700 hover:bg-white shadow-md hidden group-hover:block">◀</button>
        <button onclick="scrollRight('slider-<?= $hotel_id ?>')" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/70 p-2 rounded-full text-gray-700 hover:bg-white shadow-md hidden group-hover:block">▶</button>
      </div>
      <div class="p-5">
        <h2 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($row['title']) ?></h2>
        <div class="flex items-center justify-center mb-2">
          <span class="text-yellow-500 text-lg">★</span>
          <span class="ml-1 text-gray-700 font-medium"><?= $avg_rating ?> / 5</span>
        </div>
        <p class="text-sm text-gray-600 mb-2 line-clamp-3"><?= htmlspecialchars($row['description']) ?></p>
        <p class="text-sm text-gray-700 mb-2 font-semibold">💷 £<?= $row['price_per_day'] ?> / night</p>
        <p class="text-xs text-gray-500">👥 Max guests: <?= $row['max_guests'] ?></p>
        <a href="book.php?id=<?= $hotel_id ?>" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition">Book Now</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</section>

<?php include './partials/footer.php'; ?>

<script src="./script.js"></script>
<script>
function scrollLeft(id) {
  const container = document.getElementById(id);
  if (container) container.scrollLeft -= container.offsetWidth;
}
function scrollRight(id) {
  const container = document.getElementById(id);
  if (container) container.scrollLeft += container.offsetWidth;
}
</script>

</div>
</body>
</html>
