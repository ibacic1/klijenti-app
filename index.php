<?php
// Naslov stranice / Page title
$naslov = "Početna";

// Uključi header / Include header
include 'includes/header.php';
?>

<!-- Hero sekcija / Hero section -->
<div class="hero">
  <div class="hero-icon">👥</div>
  <h1>Sustav za upravljanje klijentima</h1>
  <p>Dodajte nove klijente ili pregledajte popis svih unesenih klijenata.</p>
  <div class="hero-buttons">
    <a href="unos.php" class="btn btn-primary">➕ Unos klijenta</a>
    <a href="ispis.php" class="btn btn-secondary">📋 Ispis klijenata</a>
  </div>
</div>

<?php
// Uključi footer / Include footer
include 'includes/footer.php';
?>