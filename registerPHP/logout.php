<?php
// registerPHP/logout.php

session_start();         // Pornește sesiunea activă
session_unset();         // Șterge toate variabilele din sesiune
session_destroy();       // Distruge sesiunea

// Redirecționează către homepage
header("Location: ../index.php");
exit;
