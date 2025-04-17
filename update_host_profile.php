<?php
session_start();
require './databasse/db.php';

$user_id = $_SESSION['user_id'];
$host_name = $_POST['host_name'];
$experience_years = $_POST['experience_years'];
$highlights = $_POST['highlights'];
$imagePath = '';

if (!empty($_FILES['profile_image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['profile_image']['name']);
    $targetPath = 'uploads/' . $imageName;
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath);
    $imagePath = $targetPath;
}

$checkStmt = $conn->prepare("SELECT user_id FROM host_profiles WHERE user_id = ?");
$checkStmt->bind_param("i", $user_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    if ($imagePath !== '') {
        $stmt = $conn->prepare("UPDATE host_profiles SET host_name=?, experience_years=?, highlights=?, profile_image=? WHERE user_id=?");
        $stmt->bind_param("sissi", $host_name, $experience_years, $highlights, $imagePath, $user_id); // ✅ fără spațiu
    } else {
        $stmt = $conn->prepare("UPDATE host_profiles SET host_name=?, experience_years=?, highlights=? WHERE user_id=?");
        $stmt->bind_param("sisi", $host_name, $experience_years, $highlights, $user_id);
    }
} else {
    $stmt = $conn->prepare("INSERT INTO host_profiles (user_id, host_name, experience_years, highlights, profile_image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $user_id, $host_name, $experience_years, $highlights, $imagePath);
}

$stmt->execute();
header("Location: my_hotels.php");
exit;
