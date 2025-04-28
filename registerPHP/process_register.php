<?php
session_start();
require '../databasse/db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = 'host'; // poți schimba rolul după nevoie

// Începem HTML-ul
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration - VacayStar</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

<?php
// Verificăm dacă username-ul există deja
$check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ❌ Username already exists. Please choose another one.
          </div>";
    exit;
}
$check_stmt->close();

// Criptăm parola
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Inserăm utilizatorul
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password_hash, $role);

if ($stmt->execute()) {
    echo "<div class='bg-green-100 text-green-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ✅ Registration successful.<br>
            <a href='login.php' class='underline text-green-800 hover:text-green-900'>Login here</a>
          </div>";
} else {
    echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ❌ Error: " . htmlspecialchars($stmt->error) . "
          </div>";
}
$stmt->close();
?>

</body>
</html>
