<?php
$host = 'localhost';
$db = 'vacaystar';
$user = 'root';
$pass = ''; // pune parola dacă ai una
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
