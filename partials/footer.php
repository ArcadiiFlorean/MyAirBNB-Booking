<footer class="w-full bg-white/20 backdrop-blur-sm border-t border-gray-300 mt-20">
  <!-- CONTAINER CU LATIME FIXA -->
  <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-10 text-gray-800 text-sm">

    <!-- Logo & Descriere -->
    <div>
      <div class="flex items-center gap-3 mb-4">
        <img src="img/logo-img.png" alt="VacayStar Logo" class="w-12 h-12 rounded-full shadow border border-gray-300">
        <h2 class="text-2xl font-bold text-blue-600">VacayStar</h2>
      </div>
      <p class="leading-relaxed">Connecting hosts and travelers worldwide with trust and comfort.</p>
    </div>

    <!-- Linkuri rapide -->
    <div>
      <h4 class="text-md font-semibold mb-4 uppercase text-gray-900 tracking-wide">Quick Links</h4>
      <ul class="space-y-2">
        <li><a href="index.php" class="hover:text-blue-600 transition">🏠 Home</a></li>
        <li><a href="my_hotels.php" class="hover:text-blue-600 transition">🏨 My Hotels</a></li>
        <li><a href="registerPHP/login.php" class="hover:text-blue-600 transition">🔐 Login</a></li>
        <li><a href="registerPHP/register.php" class="hover:text-blue-600 transition">📝 Register</a></li>
      </ul>
    </div>

    <!-- Contact & Social -->
    <div>
      <h4 class="text-md font-semibold mb-4 uppercase text-gray-900 tracking-wide">Connect</h4>
      <p>Email: <a href="mailto:support@vacaystar.com" class="text-blue-600 hover:underline">support@vacaystar.com</a></p>
      <p>Phone: <span class="text-gray-800">+44 1234 567890</span></p>
      <div class="flex items-center space-x-4 mt-4 text-xl">
        <a href="https://facebook.com" target="_blank" class="hover:text-blue-600"><i class="fab fa-facebook-square"></i></a>
        <a href="https://instagram.com" target="_blank" class="hover:text-pink-500"><i class="fab fa-instagram"></i></a>
        <a href="https://twitter.com" target="_blank" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
        <a href="https://linkedin.com" target="_blank" class="hover:text-blue-700"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>
  </div>

  <!-- Bara de jos -->
  <div class="w-full bg-white/10 backdrop-blur-sm border-t border-gray-300 text-center text-gray-500 text-xs py-4">
    &copy; <?= date('Y') ?> VacayStar. All rights reserved.
  </div>
</footer>
