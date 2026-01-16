<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../app/Repositories/ReservationRepository.php";
require_once __DIR__ . "/../config/Database.php";

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$request = new ReservationRepository();
$requests = $request->getRequestsForCoach($_SESSION["user_id"]);



// if (!isset($_SESSION['id_user']) || ($_SESSION['role'] ?? '') !== 'coach') {
//   header("Location: /login");
//   exit;
// }

$coachId = (int)$_SESSION['user_id'];
$pdo = Database::connect();

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservation WHERE id_coach = ? AND statut = 'en_attente'");
$stmt->execute([$coachId]);
$pendingCount = (int)$stmt->fetchColumn();

// $stmt = $pdo->prepare("SELECT COUNT(*) FROM seances WHERE coach_id = ? AND statut = 'reservee' AND date_seance = ?");
// $stmt->execute([$coachId, $today]);
// $todayCount = (int)$stmt->fetchColumn();

// $stmt = $pdo->prepare("SELECT COUNT(*) FROM seances WHERE coach_id = ? AND statut = 'reservee'");
// $stmt->execute([$coachId]);
// $valideCount = (int)$stmt->fetchColumn();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Coach</title>
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="/coach/disponibilite">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">Coach</span>
    </a>
    <nav class="navlinks">
      <a class="active" href="/coach/disponibilite">Dashboard</a>
      <!-- <a href="/view/profil.coach.php">Profil</a> -->
      <a href="/logout">Déconnexion</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Dashboard Coach</h1>
      <p class="h-sub">Demandes de séances à accepter/refuser (PHP).</p>
    </div>
    <div class="actions">
      <button class="btn primary" data-confirm data-confirm-title="Ajouter une séance"
        data-confirm-msg="Tu peux ouvrir un formulaire (ou page) pour créer une séance."
        data-confirm-action-hint="Créer une page create_seance.php ou un modal et POST vers SeanceController::create().">
        Ajouter une séance
      </button>
    </div>
  </div>

  <div class="grid grid-3">
    <div class="kpi">
      <div class="kpi-icon">
        <img alt="" width="20" height="20"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'><path d='M3 10h14' stroke='%23f59e0b' stroke-width='2' stroke-linecap='round'/><circle cx='6' cy='10' r='2' stroke='%23e5e7eb' stroke-width='2'/></svg>">
      </div>
      <div>
        <!-- <div class="kpi-val"><?= $pendingCount ?></div> -->
        <div class="kpi-lab">Demandes en attente</div>
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-icon">
        <img alt="" width="20" height="20"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'><path d='M4 11l3 3 9-9' stroke='%2322c55e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg>">
      </div>
      <div>
        <!-- <div class="kpi-val"><?= $todayCount ?></div> -->
        <div class="kpi-lab">Validées aujourd’hui</div>
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-icon">
        <img alt="" width="20" height="20"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'><path d='M10 2v6l4 2' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/><circle cx='10' cy='10' r='8' stroke='%2322c55e' stroke-width='2'/></svg>">
      </div>
      <div>
        <!-- <div class="kpi-val"><?= $valideCount ?></div> -->
        <div class="kpi-lab">Validées</div>
      </div>
    </div>
  </div>

  <div class="grid grid-2" style="margin-top:16px">
    <section class="card">
      <div class="card-h">
        <h2 class="card-title">Demandes</h2>
        <span class="pill wait">En attente</span>
      </div>
      <div class="card-b">
        <table class="table">
          <thead>
            <tr>
              <th>Sportif</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Durée</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($requests as $r): ?>                           
            <tr>                                                          
              <td><?= htmlspecialchars($r['prenom'].' '.$r['nom']) ?></td>
              <td><?= $r['date'] ?></td>
              <td><?= substr($r['heure_debut'],0,5) ?></td>

              <td>
                <!-- ACCEPT -->
                <form method="post" action="/coach/acceptReservation" style="display:inline;">
                  <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                  <button class="btn primary sm">Accept</button>
                </form>

                <!-- REJECT -->
                <form method="post" action="/coach/refuseReservation" style="display:inline;">
                  <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                  <button class="btn danger sm">Reject</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </section>

    <aside class="card">
  <div class="card-h">
    <h2 class="card-title">Ajouter un créneau</h2>
    <span class="pill ok">Disponibilité</span>
  </div>

  <div class="card-b">
    <p style="margin-top:0;color:var(--muted);line-height:1.6">
      Le coach crée un créneau “disponible”. Le sportif ne peut réserver que ces créneaux.
    </p>

    <!-- action + method to connect in PHP -->
    <form class="form" data-js="validate" action="/coach/addDisponibilite" method="post">
      <!-- You will set coach_id from session in PHP, no need to show it here -->
      <!-- <input type="hidden" name="coach_id" value="<?php echo $_SESSION['id']; ?>"> -->

      <div class="field">
        <label class="label" for="date_seance">Date</label>
        <input class="input" id="date_seance" name="date_seance" type="date" required />
      </div>

      <div class="field">
        <label class="label" for="heure">Heure</label>
        <input class="input" id="heure" name="heure" type="time" required />
      </div>

      <div class="field">
        <label class="label" for="duree">Durée (minutes)</label>
        <input class="input" id="duree" name="duree" type="number"  required placeholder="ex: 60" />
      </div>

      <div class="field">
        <label class="label" for="statut">Statut</label>
        <select class="select" id="statut" name="statut" required>
          <option value="disponible">disponible</option>
          <option value="reservee" disabled>reservée (auto)</option>
        </select>
        <div class="note">
          "reservée" doit être mise automatiquement quand un sportif réserve.
        </div>
      </div>

      <button class="btn primary" type="submit">
        <img alt="" width="18" height="18"
          src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='none'><path d='M4 9h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/><path d='M9 4v10' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/></svg>">
        Ajouter
      </button>
    </form>

    <div class="hr"></div>

    <div class="note">
      Règle côté PHP: empêcher deux créneaux identiques pour le même coach (même date + heure).
    </div>
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

<script src="/assets/js/main.js"></script>
</body>
</html>