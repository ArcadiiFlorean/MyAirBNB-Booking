<?php
session_start();
require '../databasse/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password_hash, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $hash, $role);
    $stmt->fetch();

    if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        // Redirecționează în funcție de rol
        if ($role === 'admin') {
            header("Location: ../admin_dashboard.php");
        } else {
            header("Location: ../my_hotels.php");
        }
        exit;
    }
}

echo "Login failed.";
