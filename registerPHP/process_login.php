<?php
session_start();
require '../databasse/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - VacayStar</title>
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
            echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
                    ❌ Login failed: Incorrect password.
                    <br><a href='login.php' class='underline text-red-800 hover:text-red-900'>Try again</a>
                  </div>";
        }
    } else {
        echo "<div class='bg-red-100 text-red-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
                ❌ Login failed: User not found.
                <br><a href='login.php' class='underline text-red-800 hover:text-red-900'>Try again</a>
              </div>";
    }
} else {
    echo "<div class='bg-yellow-100 text-yellow-700 p-6 rounded-lg shadow max-w-md w-full text-center'>
            ⚠️ Invalid request.
            <br><a href='login.php' class='underline text-yellow-800 hover:text-yellow-900'>Back to login</a>
          </div>";
}
?>

</body>
</html>
