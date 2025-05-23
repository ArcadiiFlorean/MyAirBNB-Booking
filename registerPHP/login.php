<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center"
      style="background-image: url('../img/login-img-2.jpg');">


  <!-- Logo deasupra formularului -->
 
  <div class="flex w-full max-w-5xl shadow-lg bg-white/90 rounded-lg overflow-hidden" >

    <!-- Left Image Section -->
    <div class="hidden md:block md:w-1/2 relative">
       <img src="../img/back-view-graceful-woman-swimsuit-hat-sitting-near-pool_273443-380.avif" alt="Login Side" class="h-full w-full object-cover">
         <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <h2 class="text-white text-3xl font-bold px-4 text-center">Welcome to VacayStar</h2>
      </div>
    </div>

    <!-- Right Form Section -->
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white bg-opacity-90">
      <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login to your account</h2>

      <form action="process_login.php" method="POST" class="space-y-5">
        <div>
          <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
          <input type="text" id="username" name="username" required
            class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
          <input type="password" id="password" name="password" required
            class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
          Login
        </button>

        <p class="text-center text-sm text-gray-600">Don't have an account? 
          <a href="register.php" class="text-blue-600 hover:underline">Register</a>
        </p>
      </form>
    </div>

  </div>

</body>
</html>
