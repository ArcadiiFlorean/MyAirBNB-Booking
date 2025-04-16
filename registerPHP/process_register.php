<?php
session_start();
require '../databasse/db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = 'host'; // poți schimba după nevoie

// Verificăm dacă username-ul există deja
$check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "❌ Username already exists. Please choose another one.";
    exit;
}
$check_stmt->close();

// Criptăm parola
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Inserăm utilizatorul
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password_hash, $role);

if ($stmt->execute()) {
    echo "✅ Registration successful. <a href='login.php'>Login here</a>";
} else {
    echo "❌ Error: " . $stmt->error;
}
$stmt->close();
