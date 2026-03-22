<?php
// Naslov stranice / Page title
$naslov = "Unos klijenata";

// Učitaj mjesta i zanimanja iz JSON datoteka / Load places and occupations from JSON files
$mjesta    = json_decode(file_get_contents('mjesta.json'), true);
$zanimanja = json_decode(file_get_contents('zanimanja.json'), true);

// Uključi header / Include header
include 'includes/header.php';
?>

<h2 class="page-title">➕ Unos novog klijenta</h2>

<!-- Forma za unos, koristi POST metodu / Form for input, uses POST method -->
<form action="unos.php" method="POST" class="form-card">

  <!-- Ime i prezime -->
  <div class="form-group">
    <label for="ime_prezime">Ime i prezime *</label>
    <input type="text" id="ime_prezime" name="ime_prezime" placeholder="npr. Ivan Horvat" required>
  </div>

  <!-- Adresa -->
  <div class="form-group">
    <label for="adresa">Adresa stanovanja *</label>
    <input type="text" id="adresa" name="adresa" placeholder="npr. Ilica 10" required>
  </div>

  <!-- Mjesto - dropdown koji se puni iz mjesta.json / Dropdown filled from mjesta.json -->
  <div class="form-group">
    <label for="mjesto">Mjesto *</label>
    <select id="mjesto" name="mjesto" required>
      <option value="">-- Odaberi mjesto --</option>
      <?php foreach ($mjesta as $m): ?>
        <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Email -->
  <div class="form-group">
    <label for="email">E-mail *</label>
    <input type="email" id="email" name="email" placeholder="npr. ivan@email.com" required>
  </div>

  <!-- Datum rođenja -->
  <div class="form-group">
    <label for="datum_rodenja">Datum rođenja *</label>
    <input type="date" id="datum_rodenja" name="datum_rodenja" required>
  </div>

  <!-- Zaposlen - radio gumbi / Radio buttons -->
  <div class="form-group">
    <label>Zaposlen *</label>
    <div class="radio-group">
      <label class="radio-label">
        <input type="radio" name="zaposlen" value="Da"> Da
      </label>
      <label class="radio-label">
        <input type="radio" name="zaposlen" value="Ne"> Ne
      </label>
    </div>
  </div>

  <!-- Zanimanja - checkboxovi koji se pune iz zanimanja.json / Checkboxes filled from zanimanja.json -->
  <div class="form-group">
    <label>Zanimanja</label>
    <div class="checkbox-group">
      <?php foreach ($zanimanja as $z): ?>
        <label class="checkbox-label">
          <input type="checkbox" name="zanimanja[]" value="<?= htmlspecialchars($z) ?>">
          <?= htmlspecialchars($z) ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Gumbi / Buttons -->
  <div class="form-actions">
    <button type="submit" class="btn btn-primary">💾 Spremi klijenta</button>
    <a href="ispis.php" class="btn btn-secondary">📋 Pogledaj sve klijente</a>
  </div>

</form>

<?php include 'includes/footer.php'; ?>