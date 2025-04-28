<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">


  <form action="process_login.php" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm space-y-6">

    <div>
      <label for="username" class="block text-gray-700 font-semibold mb-2">Username:</label>
      <input type="text" id="username" name="username" required
             class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <label for="password" class="block text-gray-700 font-semibold mb-2">Password:</label>
      <input type="password" id="password" name="password" required
             class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <button type="submit"
              class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
        Login
      </button>
    </div>

    <p class="text-center text-sm text-gray-600">Don't have an account? 
      <a href="register.php" class="text-blue-600 hover:underline">Register</a>
    </p>

  </form>

</body>
</html>
