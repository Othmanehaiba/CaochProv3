<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../app/Controllers/CoachController.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Coachs disponibles</title>
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="/">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">Coachs</span>
    </a>
    <nav class="navlinks">
      <a class="active" href="/">Coachs</a>
      <a href="/sportif">Dashboard Sportif</a>
      <a href="/logout">Déconnexion</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Coachs disponibles</h1>
      <p class="h-sub">Liste à remplir depuis MySQL (PHP).</p>
    </div>

    <div class="actions">
      <input class="input" style="max-width:260px" placeholder="Rechercher (front only)" />
      <select class="select" style="max-width:220px">
        <option>Toutes disciplines</option>
        <option>Football</option>
        <option>Tennis</option>
        <option>Natation</option>
      </select>
    </div>
  </div>

  <section class="card">
  <div class="card-h">
    <h2 class="card-title">Résultats</h2>
    <span class="pill">Liste</span>
  </div>

  <div class="card-b">
    <?php
      $coachesCtrl = new CoachController();
      $list = $coachesCtrl->afficherCoaches();
    ?>

    <div class="coach-grid">
      <?php foreach ($list as $c): ?>
        <div class="coach-card">
          <div class="coach-photo">
            <img 
                src="https://ui-avatars.com/api/?name=<?= urlencode($c['prenom'].' '.$c['nom']) ?>&background=0f172a&color=22c55e&size=256"
                alt="Coach photo">
          </div>

          <div class="coach-body">
            <h3 class="coach-name">
              <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
            </h3>

            <p class="coach-discipline">
              <?= htmlspecialchars($c['discipline']) ?>
            </p>

            <p class="coach-experience">
              <?= (int)$c['experience'] ?> years of experience
            </p>

            <p class="coach-desc">
              <?= htmlspecialchars($c['description']) ?>
            </p>

            <div class="coach-actions">
              <a href="/reserve?coach_id=<?= (int)$c['id'] ?>" class="btn primary sm">
                View
              </a>

              <a href="/reserve?coach_id=<?= (int)$c['id'] ?>" class="btn sm">
                Book
              </a>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="hr"></div>
  </div>
</section>


  <div class="footer">SportCoach • Template front</div>
</main>

<!-- Modal -->
<div class="modal-backdrop" id="confirmModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-label="Confirmation">
    <div class="modal-h">
      <h3 class="modal-title">Confirmer</h3>
      <button class="modal-x" type="button" data-modal-close>&times;</button>
    </div>
    <div class="modal-b">
      <p data-modal-msg style="margin-top:0;color:var(--text)"></p>
      <div class="note" data-modal-hint></div>
      <div class="modal-actions">
        <button class="btn" type="button" data-modal-close>Annuler</button>
        <button class="btn primary" type="button" data-modal-close>OK</button>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="bookModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-label="Booking">
    <div class="modal-h">
      <h3 class="modal-title">Choose availability</h3>
      <button class="modal-x" type="button" data-modal-close>&times;</button>
    </div>

    <div class="modal-b">
      <div class="note" style="margin-bottom:12px">
        Select a slot. Only "disponible" slots will appear.
      </div>

      <div id="slotsBox" class="empty">Loading...</div>

      <form id="bookForm" action="/reserve" method="post" style="display:none;margin-top:12px">
        <input type="hidden" name="seance_id" id="seance_id">
        <button class="btn primary" type="submit">Confirm booking</button>
      </form>
    </div>
  </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>