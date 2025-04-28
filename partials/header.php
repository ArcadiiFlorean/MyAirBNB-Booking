<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- ✅ Stil special pentru header -->
<link rel="stylesheet" href="./block-css/header.css">
<link rel="stylesheet" href="../general.css/settings.css">
<style></style>
<header class="fixed top-0 left-0 w-full z-50 bg-blue-900 text-white shadow-md border-b border-blue-800">

  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center py-4">
    
    <!-- Logo -->
    <div class="flex items-center gap-3">
      <a href="index.php" class="flex items-center gap-3">
        <img src="img/logo-2.png" alt="Logo" class="w-16 h-16 rounded-full object-cover">
        <h2 class="text-2xl font-bold text-blue-300 tracking-wide">VacayStar</h2>
      </a>
    </div>

    <!-- Navigation -->
    <nav class="flex items-center space-x-6 text-blue-300 font-medium text-base">
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="registerPHP/login.php" class="hover:text-white transition">Login</a>
        <a href="registerPHP/register.php" class="hover:text-white transition">Register</a>
      <?php else: ?>
        <?php if ($_SESSION['role'] === 'host'): ?>
          <a href="my_hotels.php" class="hover:text-white transition">My Hotels</a>
        <?php elseif ($_SESSION['role'] === 'admin'): ?>
          <a href="admin_dashboard.php" class="hover:text-white transition">Admin Dashboard</a>
        <?php endif; ?>
        <a href="registerPHP/logout.php" class="hover:text-red-600 transition">Logout</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
