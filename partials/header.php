<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- ✅ Stil special pentru header -->
<link rel="stylesheet" href="./block-css/header.css">
<link rel="stylesheet" href="../general.css/settings.css">

<header class="header ">
    <div class="container  mx-auto px-4 flex justify-between items-center py-4">
        
        <div class="logo flex items-center gap-2">
            <a href="index.php" class="flex items-center gap-2">
                <img src="img/logo-img.png" alt="Logo" class="logo-img w-20 h-20">
                <h2 class="text-3xl font-bold ">VacayStar</h2>
            </a>
        </div>

        <nav class="nav-links space-x-4">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="registerPHP/login.php">Login</a>
                <a href="registerPHP/register.php">Register</a>
            <?php else: ?>
                <?php if ($_SESSION['role'] === 'host'): ?>
                    <a href="my_hotels.php">My Hotels</a>
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin_dashboard.php">Admin Dashboard</a>
                <?php endif; ?>
                <a href="registerPHP/logout.php">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<hr>
