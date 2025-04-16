<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- ✅ Stil special pentru header -->
<link rel="stylesheet" href="./block-css/header.css">



<header class="header">
    <div class="container">
        <div class="logo">
            <h2><a href="index.php">VacayStar</a></h2>
        </div>
        <nav class="nav-links">
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
