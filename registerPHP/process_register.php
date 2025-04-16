<?php
// registerPHP/process_register.php

require '../databasse/db.php'; // asigură-te că calea e corectă

// Verificăm dacă formularul a fost trimis corect
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'], $_POST['role'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Parola criptată
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Pregătim interogarea SQL
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hash, $role);

    if ($stmt->execute()) {
        // Redirecționare către login după înregistrare
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid form submission.";
}
?>
