<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/Database.php";

if (!isset($_GET['coach_id'])) {
    die("Coach not found");
}

$coachId = (int)$_GET['coach_id'];

$pdo = Database::connect();

$sql = "SELECT id, id_coach, date, heure_debut, duree
        FROM disponibilite
        WHERE id_coach = ? --AND statut = 'en_attente'
        ORDER BY date, heure_debut";

$stmt = $pdo->prepare($sql);
$stmt->execute([$coachId]);
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Book a session</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<h2>Available sessions</h2>

<?php if (empty($seances)): ?>
  <p>Aucune disponibilité pour ce coach.</p>
<?php else: ?>
  <?php foreach ($seances as $s): ?>
    <form method="post" action="/reserve" style="margin-bottom:10px;">
      <input type="hidden" name="disponibilite_id" value="<?= (int)$s['id'] ?>">
      

      <div class="card">
        <strong><?= htmlspecialchars($s['date']) ?></strong>
        -
        <?= htmlspecialchars(substr($s['heure_debut'], 0, 5)) ?>
        (<?= (int)$s['duree'] ?> min)

        <br><br>

        <button type="submit" class="btn primary sm">
          Reserve
        </button>
      </div>
    </form>
  <?php endforeach; ?>
<?php endif; ?>

<a href="/coach">← Back to coaches</a>

</body>
</html>