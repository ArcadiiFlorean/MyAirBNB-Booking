<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center"
      style="background-image: url('../img/login-img-2.jpg');">

  <div class="flex w-full max-w-6xl rounded-xl overflow-hidden shadow-2xl">

    <!-- Left Image Section -->
    <div class="hidden md:block md:w-1/2 relative">
      <img src="../img/register-img.jpg" alt="Register Visual" class="h-full w-full object-cover">
      <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">

      </div>
    </div>

    <!-- Right Form Section -->
    <div class="w-full md:w-1/2 p-10 md:p-14 flex flex-col justify-center bg-white/30 backdrop-blur-md rounded-r-xl ring-1 ring-white/20 shadow-xl">

      <!-- Title -->
      <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8 tracking-tight">
        Create Your VacayStar Account
      </h2>

      <!-- Form -->
      <form action="process_register.php" method="POST" class="space-y-6 text-sm">

        <!-- Username -->
        <div>
          <label for="username" class="block text-gray-700 font-semibold mb-2">👤 Username</label>
          <input type="text" id="username" name="username" required
                placeholder="john_doe"
                class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700 font-semibold mb-2">📧 Email</label>
          <input type="email" id="email" name="email" required
                placeholder="john@example.com"
                class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-2">🔒 Password</label>
          <input type="password" id="password" name="password" required
                placeholder="••••••••"
                class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Submit -->
        <div>
          <button type="submit"
                  class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-2 rounded-lg shadow-md hover:from-green-600 hover:to-emerald-700 transition duration-300">
            ✨ Register Now
          </button>
        </div>

        <!-- Login link -->
        <p class="text-center text-sm text-gray-600 mt-4">
          Already have an account?
          <a href="login.php" class="text-green-700 font-semibold hover:underline">Login</a>
        </p>

      </form>
    </div>
  </div>

</body>
</html>
