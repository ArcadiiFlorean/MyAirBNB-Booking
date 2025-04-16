<?php
session_start();
require './databasse/db.php';
?>

<!-- Header simplu -->
<div style="background:#f0f0f0; padding:10px; display:flex; justify-content:space-between;">
    <div><h2>VacayStar</h2></div>
    <div>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="registerPHP/login.php">Login</a> |
            <a href="registerPHP/register.php">Register</a>
        <?php else: ?>
            <?php if ($_SESSION['role'] === 'host'): ?>
                <a href="my_hotels.php">My Hotels</a> |
            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a> |
            <?php endif; ?>
            <a href="registerPHP/logout.php">Logout</a>
        <?php endif; ?>
    </div>
</div>

<hr>

<!-- Afișare hoteluri -->
<h1>Available Hotels</h1>

<?php
$result = $conn->query("SELECT * FROM hotels");

while ($row = $result->fetch_assoc()) {
    echo "<div style='border:1px solid #ccc; padding:15px; margin:15px;'>";
    echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
   // Afișare toate imaginile din hotel_images
$hotel_id = $row['id'];
$img_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE hotel_id = ?");
$img_stmt->bind_param("i", $hotel_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();

while ($img = $img_result->fetch_assoc()) {
    echo "<img src='" . htmlspecialchars($img['image_path']) . "' width='200' style='margin-right:10px; margin-bottom:10px;'>";
}

$img_stmt->close();

    echo "<strong>£" . $row['price_per_day'] . " / night</strong><br>";
    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
    echo "<a href='book.php?id=" . $row['id'] . "'>Book Now</a>";
    echo "</div>";
}
?>
