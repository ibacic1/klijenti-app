<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Naslov stranice se mijenja dinamički ovisno o stranici / Page title changes dynamically -->
  <title><?= htmlspecialchars($naslov) ?> | Klijenti App</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navigacijska traka / Navigation bar -->
<nav class="navbar">
  <div class="nav-brand">🗂️ KlijentiApp</div>
  <ul class="nav-links">
    <!-- Aktivna klasa se dodaje ovisno o trenutnoj stranici / Active class added based on current page -->
    <li><a href="index.php" <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : '' ?>>Početna</a></li>
    <li><a href="unos.php"  <?= (basename($_SERVER['PHP_SELF']) == 'unos.php')  ? 'class="active"' : '' ?>>Unos klijenata</a></li>
    <li><a href="ispis.php" <?= (basename($_SERVER['PHP_SELF']) == 'ispis.php') ? 'class="active"' : '' ?>>Ispis klijenata</a></li>
  </ul>
</nav>

<!-- Glavni sadržaj / Main content -->
<main class="container">