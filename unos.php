<?php
// Naslov stranice / Page title
$naslov = "Unos klijenata";

// Učitaj mjesta i zanimanja iz JSON datoteka / Load places and occupations from JSON files
$mjesta    = json_decode(file_get_contents('mjesta.json'), true);
$zanimanja = json_decode(file_get_contents('zanimanja.json'), true);

// Varijable za poruke / Message variables
$poruka = "";
$greska = "";

// Provjeri je li forma poslana POST metodom / Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Dohvati i očisti podatke iz forme / Get and trim form data
    $ime_prezime = trim($_POST['ime_prezime'] ?? '');
    $adresa      = trim($_POST['adresa'] ?? '');
    $mjesto      = trim($_POST['mjesto'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $datum_rod   = trim($_POST['datum_rodenja'] ?? '');
    $zaposlen    = $_POST['zaposlen'] ?? '';
    $zanimanja_odabrana = $_POST['zanimanja'] ?? [];

    // Validacija - provjeri prazna polja i email format / Validate - check empty fields and email format
    if (empty($ime_prezime) || empty($adresa) || empty($mjesto) ||
        empty($email) || empty($datum_rod) || empty($zaposlen)) {
        $greska = "Sva polja su obavezna!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $greska = "Unesite ispravnu e-mail adresu!";
    } else {
        // Putanja do JSON datoteke / Path to JSON file
        $storage_path = 'storage/klijenti.json';

        // Učitaj postojeće klijente ili prazni niz / Load existing clients or empty array
        $klijenti = [];
        if (file_exists($storage_path)) {
            $klijenti = json_decode(file_get_contents($storage_path), true) ?? [];
        }

        // Generiraj novi ID / Generate new ID
        $novi_id = empty($klijenti) ? 1 : max(array_column($klijenti, 'id')) + 1;

        // Kreiraj novog klijenta / Create new client
        $novi_klijent = [
            'id'            => $novi_id,
            'ime_prezime'   => $ime_prezime,
            'adresa'        => $adresa,
            'mjesto'        => $mjesto,
            'email'         => $email,
            'datum_rodenja' => $datum_rod,
            'zaposlen'      => $zaposlen,
            'zanimanja'     => $zanimanja_odabrana
        ];

        // Dodaj klijenta u niz i spremi u JSON / Add client to array and save to JSON
        $klijenti[] = $novi_klijent;
        file_put_contents($storage_path, json_encode($klijenti, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $poruka = "Klijent <strong>" . htmlspecialchars($ime_prezime) . "</strong> uspješno je dodan! (ID: $novi_id)";
    }
}

include 'includes/header.php';
?>

<h2 class="page-title">➕ Unos novog klijenta</h2>

<!-- Prikaz poruke uspjeha ili greške / Show success or error message -->
<?php if ($poruka): ?>
  <div class="alert alert-success"><?= $poruka ?></div>
<?php endif; ?>
<?php if ($greska): ?>
  <div class="alert alert-error"><?= htmlspecialchars($greska) ?></div>
<?php endif; ?>

<!-- Forma za unos, koristi POST metodu / Form for input, uses POST method -->
<form action="unos.php" method="POST" class="form-card">

  <div class="form-group">
    <label for="ime_prezime">Ime i prezime *</label>
    <!-- Zadrži vrijednost nakon greške / Keep value after error -->
    <input type="text" id="ime_prezime" name="ime_prezime" placeholder="npr. Ivan Horvat"
           value="<?= isset($_POST['ime_prezime']) && $greska ? htmlspecialchars($_POST['ime_prezime']) : '' ?>" required>
  </div>

  <div class="form-group">
    <label for="adresa">Adresa stanovanja *</label>
    <input type="text" id="adresa" name="adresa" placeholder="npr. Ilica 10"
           value="<?= isset($_POST['adresa']) && $greska ? htmlspecialchars($_POST['adresa']) : '' ?>" required>
  </div>

  <!-- Dropdown za mjesto / Dropdown for place -->
  <div class="form-group">
    <label for="mjesto">Mjesto *</label>
    <select id="mjesto" name="mjesto" required>
      <option value="">-- Odaberi mjesto --</option>
      <?php foreach ($mjesta as $m): ?>
        <option value="<?= htmlspecialchars($m) ?>"
          <?= (isset($_POST['mjesto']) && $_POST['mjesto'] === $m && $greska) ? 'selected' : '' ?>>
          <?= htmlspecialchars($m) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="email">E-mail *</label>
    <input type="email" id="email" name="email" placeholder="npr. ivan@email.com"
           value="<?= isset($_POST['email']) && $greska ? htmlspecialchars($_POST['email']) : '' ?>" required>
  </div>

  <div class="form-group">
    <label for="datum_rodenja">Datum rođenja *</label>
    <input type="date" id="datum_rodenja" name="datum_rodenja"
           value="<?= isset($_POST['datum_rodenja']) && $greska ? htmlspecialchars($_POST['datum_rodenja']) : '' ?>" required>
  </div>

  <!-- Radio gumbi za zaposlen / Radio buttons for employed -->
  <div class="form-group">
    <label>Zaposlen *</label>
    <div class="radio-group">
      <label class="radio-label">
        <input type="radio" name="zaposlen" value="Da"
          <?= (isset($_POST['zaposlen']) && $_POST['zaposlen'] === 'Da' && $greska) ? 'checked' : '' ?>> Da
      </label>
      <label class="radio-label">
        <input type="radio" name="zaposlen" value="Ne"
          <?= (isset($_POST['zaposlen']) && $_POST['zaposlen'] === 'Ne' && $greska) ? 'checked' : '' ?>> Ne
      </label>
    </div>
  </div>

  <!-- Checkboxovi za zanimanja / Checkboxes for occupations -->
  <div class="form-group">
    <label>Zanimanja</label>
    <div class="checkbox-group">
      <?php foreach ($zanimanja as $z): ?>
        <label class="checkbox-label">
          <input type="checkbox" name="zanimanja[]" value="<?= htmlspecialchars($z) ?>"
            <?= (isset($_POST['zanimanja']) && in_array($z, $_POST['zanimanja']) && $greska) ? 'checked' : '' ?>>
          <?= htmlspecialchars($z) ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">💾 Spremi klijenta</button>
    <a href="ispis.php" class="btn btn-secondary">📋 Pogledaj sve klijente</a>
  </div>

</form>

<?php include 'includes/footer.php'; ?>