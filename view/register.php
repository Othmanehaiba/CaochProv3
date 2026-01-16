<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription</title>
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="/">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">MVP</span>
    </a>
    <nav class="navlinks">
      <a href="/">Coachs</a>
      <a href="/login">Connexion</a>
      <a class="active" href="/signup">Inscription</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Inscription</h1>
      <p class="h-sub">Créer un compte Sportif ou Coach (logique à faire côté PHP).</p>
    </div>
  </div>

  <section class="card">
    <div class="card-h">
      <h2 class="card-title">Informations</h2>
      <span class="pill">Formulaire</span>
    </div>
    <div class="card-b">
      <!-- action à brancher côté PHP -->
      <form class="form" data-js="validate" action="/signup" method="post">
        <input type="hidden" name="action" value="register">
        <div class="grid grid-2">
          <div class="field">
            <label class="label" for="prenom">Prénom</label>
            <input class="input" id="prenom" name="prenom" required placeholder="Prénom" />
          </div>
          <div class="field">
            <label class="label" for="nom">Nom</label>
            <input class="input" id="nom" name="nom" required placeholder="Nom" />
          </div>
        </div>

        <div class="grid grid-2">
          <div class="field">
            <label class="label" for="email">Email</label>
            <input class="input" id="email" name="email" type="email" required data-validate="email" placeholder="ex: nom@mail.com" />
          </div>
          <div class="field">
            <label class="label" for="phone">Téléphone</label>
            <input class="input" id="phone" name="phone" data-validate="phone" placeholder="ex: +212..." />
          </div>
        </div>

        <div class="field">
          <label class="label" for="role">Je suis</label>
          <select class="select" id="role" name="role" required>
            <option value="">Choisir...</option>
            <option value="sportif">Sportif</option>
            <option value="coach">Coach</option>
          </select>
          <div id="coachFields" style="display:none;">
          <div class="field">
            <label class="label" for="experience">Annee d'experience</label>
            <input class="input" id="experience" name="experience" type="number" placeholder="5" />
          </div>

          <div class="field">
            <label class="label" for="discipline">Discipline</label>
            <select class="select" id="discipline" name="discipline">
              <option value="">Choisir...</option>
              <option value="fitness">Fitness</option>
              <option value="yoga">Yoga</option>
              <option value="musculation">Musculation</option>
              <option value="pilates">Pilates</option>
              <option value="crossfit">CrossFit</option>
            </select>
          </div>

          <div class="field">
            <label class="label" for="description">Description</label>
            <textarea class="input" id="description" name="description"></textarea>
          </div>
        </div>
        </div>

        <div class="grid grid-2">
          <div class="field">
            <label class="label" for="password">Mot de passe</label>
            <input class="input" id="password" name="password" type="password" required data-validate="password" />
          </div>
          <div class="field">
            <label class="label" for="password2">Confirmer</label>
            <input class="input" id="password2" name="password2" type="password" required data-validate="match" data-match="#password" />
          </div>
        </div>

        <button class="btn primary" name="submit" type="submit">Créer mon compte</button>
      </form>
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
<script>
  const roleSelect = document.getElementById("role");
  const coachFields = document.getElementById("coachFields");

  function toggleCoachFields() {
    if (!roleSelect || !coachFields) return;

    if (roleSelect.value === "coach") {
      coachFields.style.display = "block";
      document.getElementById("experience").required = true;
      document.getElementById("discipline").required = true;
      document.getElementById("description").required = true;
    } else {
      coachFields.style.display = "none";
      document.getElementById("experience").required = false;
      document.getElementById("discipline").required = false;
      document.getElementById("description").required = false;
    }
  }

  // au chargement
  toggleCoachFields();

  // quand tu changes
  roleSelect.addEventListener("change", toggleCoachFields);
</script>
<script src="/assets/js/main.js"></script>
</body>
</html>