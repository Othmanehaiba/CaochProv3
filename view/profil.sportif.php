<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/Database.php";
if (!isset($_SESSION['id']) || ($_SESSION['role'] ?? '') !== 'sportif') {
  header("Location: /login");
  exit;
}
$sportifId = (int)$_SESSION['id'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Sportif</title>
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="/sportif">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">Profil Sportif</span>
    </a>
    <nav class="navlinks">
      <a href="/sportif">Dashboard</a>
      <a class="active" href="/sportif/details">Profil</a>
      <a href="/logout">Déconnexion</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Mon profil</h1>
      <p class="h-sub">Données affichées et modifiées via PHP.</p>
    </div>
  </div>

  <div class="grid grid-2">
    <section class="card">
      <div class="card-h">
        <h2 class="card-title">Informations</h2>
        <span class="pill">Compte</span>
      </div>
      <div class="card-b">
        <div class="empty">Affichage infos sportif (nom, email, téléphone...) via PHP.</div>
        <div class="hr"></div>

        <!-- action à brancher côté PHP -->
        <form class="form" data-js="validate" action="#" method="post">
          <div class="field">
            <label class="label">Nom complet</label>
            <input class="input" name="fullname" placeholder="Nom Prénom" required />
          </div>
          <div class="field">
            <label class="label">Email</label>
            <input class="input" name="email" data-validate="email" required />
          </div>
          <div class="field">
            <label class="label">Téléphone</label>
            <input class="input" name="phone" data-validate="phone" />
          </div>
          <button class="btn primary" type="submit">Enregistrer</button>
        </form>
      </div>
    </section>

    <aside class="card">
      <div class="card-h">
        <h2 class="card-title">Sécurité</h2>
        <span class="pill">Mot de passe</span>
      </div>
      <div class="card-b">
        <form class="form" data-js="validate" action="#" method="post">
          <div class="field">
            <label class="label">Nouveau mot de passe</label>
            <input class="input" type="password" name="new_password" data-validate="password" required />
          </div>
          <div class="field">
            <label class="label">Confirmer</label>
            <input class="input" type="password" name="new_password2" data-validate="match" data-match="[name='new_password']" required />
          </div>
          <button class="btn warn" type="submit">Mettre à jour</button>
        </form>

        <div class="hr"></div>

        <button class="btn danger"
          data-confirm
          data-confirm-title="Supprimer mon compte"
          data-confirm-msg="Cette action est définitive. Continuer ?"
          data-confirm-action-hint="Brancher un POST vers UserController::deleteSelf() + CSRF.">
          Supprimer le compte
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

<script src="/assets/js/main.js"></script>
</body>
</html>