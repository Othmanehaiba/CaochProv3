<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion</title>
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="/">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach
      <span class="badge">MVP</span>
    </a>
    <nav class="navlinks">
      <a href="/">Coachs</a>
      <a class="active" href="/login">Connexion</a>
      <a href="/signup">Inscription</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Connexion</h1>
      <p class="h-sub">Accès sportif, coach ou admin (à gérer côté PHP).</p>
    </div>
  </div>
  <div class="grid grid-2">
    <section class="card">
      <div class="card-h">
        <h2 class="card-title">Se connecter</h2>
        <span class="pill">Sécurisé</span>
      </div>
      <div class="card-b">
        <!-- action + method à brancher côté PHP -->
        <form class="form" data-js="validate" action="/login" method="post">
          <input type="hidden" name="action" value="login">
          <div class="field">
            <label class="label" for="email">Email</label>
            <input class="input" id="email" name="email" type="email" required data-validate="email" placeholder="ex: nom@mail.com" />
          </div>

          <div class="field">
            <label class="label" for="password">Mot de passe</label>
            <input class="input" id="password" name="password" type="password" required data-validate="password" placeholder="8+ caractères" />
          </div>

          <div class="field">
            <label class="label" for="role">Rôle</label>
            <select class="select" id="role" name="role" required>
              <option value="">Choisir...</option>
              <option value="sportif">Sportif</option>
              <option value="coach">Coach</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <button class="btn primary" name="submit" type="submit">
            <img alt="" width="18" height="18"
              src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='none'><path d='M6 9h9' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/><path d='M11 5l4 4-4 4' stroke='%2322c55e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/><path d='M3 3v12' stroke='%231f2937' stroke-width='2' stroke-linecap='round'/></svg>">
            Continuer
          </button>
        </form>
      </div>
    </section>

    <aside class="card">
      <div class="card-h">
        <h2 class="card-title">Aide</h2>
        <span class="pill">Info</span>
      </div>
      <div class="card-b">
        <p style="margin-top:0;color:var(--muted);line-height:1.6">
          Validation JS basique. Le contrôle final (sécurité) se fait côté serveur.
        </p>
        <div class="hr"></div>
        <a class="btn" href="register.php">Créer un compte</a>
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