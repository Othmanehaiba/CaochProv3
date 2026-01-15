<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Sportif</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="dashboard.sportif.php">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">Sportif</span>
    </a>
    <nav class="navlinks">
      <a href="coaches.php">Coachs</a>
      <a class="active" href="dashboard.sportif.php">Dashboard</a>
      <a href="profil.sportif.php">Profil</a>
      <a href="logout.php">Déconnexion</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Dashboard Sportif</h1>
      <p class="h-sub">Mes séances + statistiques (données via PHP).</p>
    </div>
    <div class="actions">
      <a class="btn primary" href="coach.php">Réserver une séance</a>
    </div>
  </div>

  <div class="grid grid-3">
    <div class="kpi">
      <div class="kpi-icon">
        <img alt="" width="20" height="20"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'><path d='M5 10h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/><path d='M10 5v10' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/></svg>">
      </div>
      <div>
        <div class="kpi-val"><!-- PHP -->0</div>
        <div class="kpi-lab">Séances à venir</div>
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-icon">
        <img alt="" width="20" height="20"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'><path d='M4 11l3 3 9-9' stroke='%2322c55e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg>">
      </div>
      <div>
        <div class="kpi-val"><!-- PHP -->0</div>
        <div class="kpi-lab">Séances terminées</div>
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-icon">
        <img alt="" width="20" height="20"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'><path d='M10 2v6l4 2' stroke='%23f59e0b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/><circle cx='10' cy='10' r='8' stroke='%23e5e7eb' stroke-width='2'/></svg>">
      </div>
      <div>
        <div class="kpi-val"><!-- PHP -->0</div>
        <div class="kpi-lab">En attente</div>
      </div>
    </div>
  </div>

  <div class="grid grid-2" style="margin-top:16px">
    <section class="card">
      <div class="card-h">
        <h2 class="card-title">Mes séances</h2>
        <span class="pill">Table</span>
      </div>
      <div class="card-b">
        <table class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Heure</th>
              <th>Coach</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            session_start();
            require_once __DIR__ . "/../app/Controllers/SportifController.php";
            
            $sportifId = (int)($_SESSION['user_id'] ?? 0);
            
            $ctrl = new SportifController();
            $seances = $ctrl->afficherMesSeances($sportifId);
            ?>
            
            <?php if (empty($seances)): ?>
              <tr>
                <td colspan="5" class="empty">Aucune séance réservée.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($seances as $s): ?>
                <tr>
                  <td><?= htmlspecialchars($s['date_seance']) ?></td>
                  <td><?= htmlspecialchars(substr($s['heure'], 0, 5)) ?></td>
                  <td><?= htmlspecialchars($s['coach_prenom'] . ' ' . $s['coach_nom']) ?></td>
                  <td>
                    <span class="pill <?= $s['seance_statut'] === 'reservee' ? 'ok' : 'wait' ?>">
                      <?= htmlspecialchars($s['seance_statut']) ?>
                    </span>
                  </td>
                  <td>
                    <!-- Exemple bouton annuler (à brancher en PHP ensuite) -->
                    <form action="../actions/cancel_reservation.php" method="post" style="display:inline;">
                      <input type="hidden" name="reservation_id" value="<?= (int)$s['reservation_id'] ?>">
                      <button class="btn danger sm" type="submit"
                        onclick="return confirm('Annuler cette réservation ?');">
                        Annuler
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
</tbody>

        </table>

        <div class="note" style="margin-top:12px">
          Annuler / modifier : fais-le côté PHP (vérifie le propriétaire, statut, CSRF).
        </div>
      </div>
    </section>

    <aside class="card">
      <div class="card-h">
        <h2 class="card-title">Prochaine séance</h2>
        <span class="pill wait">À venir</span>
      </div>
      <div class="card-b">
        <div class="empty">
          Bloc “prochaine séance” (infos coach, date, durée) à afficher depuis PHP.
        </div>
        <div class="hr"></div>
        <button class="btn danger"
          data-confirm
          data-confirm-title="Annuler la séance"
          data-confirm-msg="Voulez-vous annuler cette réservation ?"
          data-confirm-action-hint="Brancher ici un POST vers ReservationController::cancel() avec CSRF.">
          Annuler (exemple)
        </button>
      </div>
    </aside>
  </div>

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

<script src="assets/js/app.js"></script>
</body>
</html>
