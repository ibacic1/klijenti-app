<?php
// Naslov stranice / Page title
$naslov = "Ispis klijenata";

// Učitaj klijente iz JSON datoteke / Load clients from JSON file
$storage_path = 'storage/klijenti.json';
$svi_klijenti = [];
if (file_exists($storage_path)) {
    $svi_klijenti = json_decode(file_get_contents($storage_path), true) ?? [];
}

// Uključi header / Include header
include 'includes/header.php';
?>

<h2 class="page-title">📋 Popis klijenata</h2>

<?php if (empty($svi_klijenti)): ?>
  <!-- Poruka ako nema klijenata / Message if no clients -->
  <div class="alert alert-info">Nema unesenih klijenata. <a href="unos.php">Dodaj prvog klijenta →</a></div>
<?php else: ?>

  <p class="filter-info">Ukupno klijenata: <strong><?= count($svi_klijenti) ?></strong></p>

  <!-- Wrapper za horizontalni scroll na mobitelu / Wrapper for horizontal scroll on mobile -->
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
        <?php foreach ($svi_klijenti as $k):
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
            <!-- Badge zeleni/crveni ovisno o zaposlenju / Green/red badge based on employment -->
            <span class="badge <?= $k['zaposlen'] === 'Da' ? 'badge-green' : 'badge-red' ?>">
              <?= htmlspecialchars($k['zaposlen']) ?>
            </span>
          </td>
          <td>
            <?php if (!empty($k['zanimanja'])): ?>
              <?= implode(', ', array_map('htmlspecialchars', $k['zanimanja'])) ?>
              <!-- Broj zanimanja u zagradi / Number of occupations in brackets -->
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

<?php include 'includes/footer.php'; ?>