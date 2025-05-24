<?php
session_start();
require '../databasse/db.php';

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = 'host';

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

<body class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-r from-blue-200 via-white to-green-200">


<?php
// Validare simplă
if (empty($username) || empty($email) || empty($password)) {
    echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ❌ All fields are required.
          </div>";
    exit;
}

// Verificăm dacă username-ul există deja
$check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_username->bind_param("s", $username);
$check_username->execute();
$check_username->store_result();

if ($check_username->num_rows > 0) {
    echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ❌ Username already exists. Please choose another one.
          </div>";
    exit;
}
$check_username->close();

// Verificăm dacă emailul există deja
$check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ❌ Email already registered. Try logging in.
          </div>";
    exit;
}
$check_email->close();

// Criptăm parola
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Inserăm utilizatorul
$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $password_hash, $role);


if ($stmt->execute()) {
    echo "
    <div class='bg-green-100 text-green-800 p-6 rounded-xl shadow-lg max-w-md w-full mx-auto text-center animate-fade-in'>
        <div class='text-3xl mb-2'>✅</div>
        <h2 class='text-xl font-semibold mb-1'>Registration successful!</h2>
        <p class='mb-3'>You can now log in to your account.</p>
        <a href='login.php' class='inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition'>
            Login here
        </a>
    </div>
    ";
} else {
    echo "
    <div class='bg-red-100 text-red-800 p-6 rounded-xl shadow-lg max-w-md w-full mx-auto text-center animate-fade-in'>
        <div class='text-3xl mb-2'>❌</div>
        <h2 class='text-xl font-semibold mb-1'>Something went wrong!</h2>
        <p class='mb-2'>". htmlspecialchars($stmt->error) ."</p>
        <a href='register.php' class='inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition'>
            Try again
        </a>
    </div>
    ";
}
?>



</body>
</html>
