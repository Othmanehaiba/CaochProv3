<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../app/Controllers/AdminController.php";

$admin = new AdminController();
$users = $admin->afficherProfiles(); 

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

<header class="topbar">
  <div class="nav">
    <a class="brand" href="dashboard.admin.php">
      <img alt="logo" width="24" height="24"
        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none'><path d='M6 14c2.5-6 9.5-6 12 0' stroke='%2322c55e' stroke-width='2' stroke-linecap='round'/><path d='M7 7h10' stroke='%23e5e7eb' stroke-width='2' stroke-linecap='round'/></svg>">
      SportCoach <span class="badge">Admin</span>
    </a>
    <nav class="navlinks">
      <a class="active" href="dashboard.admin.php">Dashboard</a>
      <a href="logout.php">Déconnexion</a>
    </nav>
  </div>
</header>

<main class="container">
  <div class="header">
    <div>
      <h1 class="h-title">Dashboard Admin</h1>
      <p class="h-sub">Gérer tous les comptes (sportifs + coachs) et supprimer.</p>
    </div>
    <div class="actions">
      <input class="input" style="max-width:260px" placeholder="Recherche (front only)" />
      <select class="select" style="max-width:200px">
        <option>Tous rôles</option>
        <option>Sportifs</option>
        <option>Coachs</option>
      </select>
    </div>
  </div>

  <section class="card">
    <div class="card-h">
      <h2 class="card-title">Comptes</h2>
      <span class="pill">Admin</span>
    </div>
    <div class="card-b">

      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          
          <tbody>

<?php if (empty($users)): ?>
    <tr>
        <td colspan="5" class="empty">No users found.</td>
    </tr>
<?php else: ?>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= (int)$u['id'] ?></td>

            <td>
                <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?>
            </td>

            <td>
                <?= htmlspecialchars($u['email']) ?>
            </td>

            <td>
                <span class="pill <?= $u['role'] === 'coach' ? 'ok' : '' ?>">
                    <?= htmlspecialchars($u['role']) ?>
                </span>
            </td>

            <td>
              <form action="../app/actions/delete_user.php" method="post" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                <button class="btn danger sm" type="submit" onclick="return confirm('Delete this user?');">
                  Delete
                </button>
              </form>
            </td>

        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</tbody>

        </tbody>
      </table>

      

      <div class="hr"></div>

      <button class="btn danger"
        data-confirm
        data-confirm-title="Supprimer un compte"
        data-confirm-msg="Supprimer ce compte définitivement ?"
        data-confirm-action-hint="Brancher un POST vers AdminController::deleteUser($id) + CSRF.">
        Supprimer (exemple)
      </button>

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

<script src="assets/js/app.js"></script>
</body>
</html>
