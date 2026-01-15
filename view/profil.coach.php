<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Coach</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="dashboard.coach.php">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">Profil Coach</span>
    </a>
    <nav class="navlinks">
      <a href="dashboard.coach.php">Dashboard</a>
      <a class="active" href="profil.coach.php">Profil</a>
      <a href="logout.php">Déconnexion</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Mon profil (Coach)</h1>
      <p class="h-sub">Modifie tes infos professionnelles (PHP).</p>
    </div>
  </div>

  <div class="grid grid-2">
    <section class="card">
      <div class="card-h">
        <h2 class="card-title">Profil professionnel</h2>
        <span class="pill">Coach</span>
      </div>
      <div class="card-b">
        <div class="empty">Photo + infos coach (affichage via PHP).</div>
        <div class="hr"></div>

        <form class="form" data-js="validate" action="#" method="post" enctype="multipart/form-data">
          <div class="grid grid-2">
            <div class="field">
              <label class="label">Nom</label>
              <input class="input" name="nom" required />
            </div>
            <div class="field">
              <label class="label">Prénom</label>
              <input class="input" name="prenom" required />
            </div>
          </div>

          <div class="grid grid-2">
            <div class="field">
              <label class="label">Discipline</label>
              <input class="input" name="discipline" required placeholder="Football / Tennis..." />
            </div>
            <div class="field">
              <label class="label">Années d'expérience</label>
              <input class="input" name="experience" type="number" min="0" required />
            </div>
          </div>

          <div class="field">
            <label class="label">Description courte</label>
            <textarea class="textarea" name="description" placeholder="Ton style de coaching, objectifs..."></textarea>
          </div>

          <div class="field">
            <label class="label">Certifications (texte)</label>
            <input class="input" name="certifications" placeholder="ex: Diplôme..., fédération..." />
          </div>

          <div class="field">
            <label class="label">Photo</label>
            <input class="input" name="photo" type="file" accept="image/*" />
          </div>

          <button class="btn primary" type="submit">Enregistrer</button>
        </form>
      </div>
    </section>

    <aside class="card">
      <div class="card-h">
        <h2 class="card-title">Disponibilités</h2>
        <span class="pill">Créneaux</span>
      </div>
      <div class="card-b">
        <div class="note">
          MVP: tu peux gérer les créneaux comme “séances disponibles” (date/heure/durée/statut).
        </div>

        <div class="empty" style="margin-top:12px">
          Liste des créneaux à afficher via PHP (mes séances “disponibles”).
        </div>

        <div class="hr"></div>

        <button class="btn warn"
          data-confirm
          data-confirm-title="Modifier un créneau"
          data-confirm-msg="Ouvrir un formulaire de modification ?"
          data-confirm-action-hint="Créer une page edit_seance.php?id=... ou un POST vers SeanceController::update().">
          Modifier (exemple)
        </button>

        <button class="btn danger"
          data-confirm
          data-confirm-title="Supprimer un créneau"
          data-confirm-msg="Supprimer ce créneau ?"
          data-confirm-action-hint="POST vers SeanceController::delete() + CSRF.">
          Supprimer (exemple)
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
