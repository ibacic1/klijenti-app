<?php
// Naslov stranice / Page title
$naslov = "Ispis klijenata";

// Učitaj klijente iz JSON datoteke / Load clients from JSON file
$storage_path = 'storage/klijenti.json';
$svi_klijenti = [];
if (file_exists($storage_path)) {
    $svi_klijenti = json_decode(file_get_contents($storage_path), true) ?? [];
}

// Dohvati odabrano slovo iz URL-a / Get selected letter from URL
$odabrano_slovo = strtoupper($_GET['slovo'] ?? '');

// Filtriraj klijente po odabranom slovu / Filter clients by selected letter
if ($odabrano_slovo !== '') {
    $klijenti = array_filter($svi_klijenti, function($k) use ($odabrano_slovo) {
        // Provjeri počinje li mjesto s odabranim slovom / Check if place starts with selected letter
        return strtoupper(mb_substr($k['mjesto'], 0, 1, 'UTF-8')) === $odabrano_slovo;
    });
} else {
    $klijenti = $svi_klijenti;
}

// Hrvatska abeceda / Croatian alphabet
$abeceda = [
    'A','B','C','Č','Ć','D','Đ','E','F','G','H','I','J','K','L',
    'Lj','M','N','Nj','O','P','R','S','Š','T','U','V','Z','Ž'
];

include 'includes/header.php';
?>

<h2 class="page-title">📋 Popis klijenata</h2>

<?php if (empty($svi_klijenti)): ?>
  <!-- Poruka ako nema klijenata / Message if no clients -->
  <div class="alert alert-info">Nema unesenih klijenata. <a href="unos.php">Dodaj prvog klijenta →</a></div>
<?php else: ?>

  <!-- Abecedni filter / Alphabet filter -->
  <div class="abeceda">
    <!-- Link za prikaz svih klijenata / Link to show all clients -->
    <a href="ispis.php" class="slovo <?= $odabrano_slovo === '' ? 'active' : '' ?>">Svi</a>
    <?php foreach ($abeceda as $slovo): ?>
      <!-- Link za svako slovo / Link for each letter -->
      <a href="ispis.php?slovo=<?= urlencode($slovo) ?>"
         class="slovo <?= $odabrano_slovo === $slovo ? 'active' : '' ?>">
        <?= htmlspecialchars($slovo) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($odabrano_slovo !== '' && empty($klijenti)): ?>
    <!-- Poruka ako nema klijenata za odabrano slovo / Message if no clients for selected letter -->
    <div class="alert alert-warning">
      🔍 Nema klijenata čije mjesto počinje sa slovom <strong><?= htmlspecialchars($odabrano_slovo) ?></strong>.
    </div>

  <?php else: ?>

    <!-- Info o trenutnom filteru / Info about current filter -->
    <?php if ($odabrano_slovo !== ''): ?>
      <p class="filter-info">
        Prikazani klijenti čije mjesto počinje sa slovom
        <strong><?= htmlspecialchars($odabrano_slovo) ?></strong>
        (<?= count($klijenti) ?> rezultat<?= count($klijenti) !== 1 ? 'a' : '' ?>)
      </p>
    <?php else: ?>
      <p class="filter-info">Ukupno klijenata: <strong><?= count($svi_klijenti) ?></strong></p>
    <?php endif; ?>

    <div class="table-wrapper">
      <table class="klijenti-tablica">
        <thead>
          <tr>
            <th>ID</th>
            <th>Ime i prezime</th>
            <th>Adresa</th>
            <th>Mjesto</th>
            <th>E-mail</th>
            <th>Datum rođenja</th>
            <th>Zaposlen</th>
            <th>Zanimanja</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($klijenti as $k):
            // Plava boja ako klijent ima više od 2 zanimanja / Blue color if client has more than 2 occupations
            $istaknuti = count($k['zanimanja'] ?? []) > 2;
          ?>
          <tr class="<?= $istaknuti ? 'istaknuti' : '' ?>">
            <td><?= (int)$k['id'] ?></td>
            <td><?= htmlspecialchars($k['ime_prezime']) ?></td>
            <td><?= htmlspecialchars($k['adresa']) ?></td>
            <td><?= htmlspecialchars($k['mjesto']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($k['email']) ?>"><?= htmlspecialchars($k['email']) ?></a></td>
            <td><?= htmlspecialchars($k['datum_rodenja']) ?></td>
            <td>
              <span class="badge <?= $k['zaposlen'] === 'Da' ? 'badge-green' : 'badge-red' ?>">
                <?= htmlspecialchars($k['zaposlen']) ?>
              </span>
            </td>
            <td>
              <?php if (!empty($k['zanimanja'])): ?>
                <?= implode(', ', array_map('htmlspecialchars', $k['zanimanja'])) ?>
                <span class="zanimanja-count">(<?= count($k['zanimanja']) ?>)</span>
              <?php else: ?>
                <em>—</em>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Legenda za plavi redak / Legend for blue row -->
    <div class="legenda">
      <span class="legenda-uzorak istaknuti-uzorak"></span>
      Klijenti s više od 2 zanimanja označeni su plavom bojom
    </div>

  <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>