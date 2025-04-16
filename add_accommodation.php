<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: registerPHP/login.php");
    exit;
}
?>

<form action="process_add_hotel.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Hotel Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price per Day" required>
    <input type="number" step="0.01" name="card_fee" placeholder="Card Fee" required>
    <input type="number" name="discount" placeholder="Discount %" required>
    <input type="number" name="vat" value="15" placeholder="VAT %" required>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Add Hotel</button>
</form>
