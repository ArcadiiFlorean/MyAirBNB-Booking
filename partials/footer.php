<footer class="bg-gray-100 border-t border-gray-300 py-8 mt-20">
  <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-700 text-sm">

    <!-- Logo & Description -->
    <div>
      <div class="flex items-center gap-3 mb-3">
        <img src="img/logo-img.png" alt="VacayStar Logo" class="w-12 h-12 rounded-full">
        <h2 class="text-xl font-bold text-blue-600">VacayStar</h2>
      </div>
      <p>Discover amazing places to stay. Our mission is to connect hosts and travelers across the globe with comfort and trust.</p>
    </div>

    <!-- Navigation Links -->
    <div>
      <h4 class="text-md font-semibold mb-3 text-gray-900">Quick Links</h4>
      <ul class="space-y-2">
        <li><a href="index.php" class="hover:underline">Home</a></li>
        <li><a href="my_hotels.php" class="hover:underline">My Hotels</a></li>
        <li><a href="registerPHP/login.php" class="hover:underline">Login</a></li>
        <li><a href="registerPHP/register.php" class="hover:underline">Register</a></li>
      </ul>
    </div>

    <!-- Contact & Social -->
    <div>
      <h4 class="text-md font-semibold mb-3 text-gray-900">Connect</h4>
      <p>Email: support@vacaystar.com</p>
      <p>Phone: +44 1234 567890</p>
      <div class="flex space-x-4 mt-3 text-xl">
        <a href="https://facebook.com" target="_blank" class="hover:text-blue-600"><i class="fab fa-facebook-square"></i></a>
        <a href="https://instagram.com" target="_blank" class="hover:text-pink-500"><i class="fab fa-instagram"></i></a>
        <a href="https://twitter.com" target="_blank" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
        <a href="https://linkedin.com" target="_blank" class="hover:text-blue-700"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>
  </div>

  <div class="text-center text-gray-500 text-xs mt-6">
    &copy; <?= date('Y') ?> VacayStar. All rights reserved.
  </div>
</footer>
