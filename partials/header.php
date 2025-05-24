<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-blue-400 border-b border-white/20 shadow-sm">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

    <!-- Logo + Brand -->
    <a href="index.php" class="flex items-center gap-3 group">
      <img src="img/logo-2.png" alt="Logo" class="w-12 h-12 rounded-full object-cover border-2 border-green-500 shadow-sm group-hover:scale-105 transition duration-300">
      <span class="text-2xl font-bold text-gray-800 group-hover:text-white transition duration-300 tracking-tight">
        VacayStar
      </span>
    </a>

    <!-- Hamburger (mobil) -->
    <button id="nav-toggle" class="md:hidden text-gray-800 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Navigation (desktop) -->
    <nav id="nav-menu" class="hidden md:flex gap-6 items-center text-gray-700 font-medium">
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="registerPHP/login.php" class="hover:text-white transition">Login</a>
        <a href="registerPHP/register.php" class="hover:text-white transition">Register</a>
      <?php else: ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'host'): ?>
          <a href="my_hotels.php" class="hover:text-green-600 transition">My Hotels</a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="admin_dashboard.php" class="hover:text-green-600 transition">Admin Dashboard</a>
        <?php endif; ?>
        <a href="registerPHP/logout.php" class="text-red-500 hover:text-red-700 transition">Logout</a>
      <?php endif; ?>
    </nav>
  </div>

  <!-- Responsive Nav (mobile) -->
  <div id="mobile-menu" class="md:hidden hidden px-6 pb-4 flex flex-col gap-3 text-gray-700 bg-white/90 backdrop-blur-sm">
    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="registerPHP/login.php" class="hover:text-green-600 transition">Login</a>
      <a href="registerPHP/register.php" class="hover:text-green-600 transition">Register</a>
    <?php else: ?>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'host'): ?>
        <a href="my_hotels.php" class="hover:text-green-600 transition">My Hotels</a>
      <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php" class="hover:text-green-600 transition">Admin Dashboard</a>
      <?php endif; ?>
      <a href="registerPHP/logout.php" class="text-red-500 hover:text-red-700 transition">Logout</a>
    <?php endif; ?>
  </div>

  <!-- Script pentru meniu mobil -->
  <script>
    const toggle = document.getElementById('nav-toggle');
    const menu = document.getElementById('mobile-menu');
    toggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>
</header>
