<?php
session_start();
require '../databasse/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // 🔁 Redirecționează la pagina hotelurilor proprii
            header("Location: ../my_hotels.php");
            exit;
        } else {
            echo "❌ Login failed: Incorrect password.";
        }
    } else {
        echo "❌ Login failed: User not found.";
    }
} else {
    echo "⚠️ Invalid request.";
}
