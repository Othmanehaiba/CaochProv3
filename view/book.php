<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . "/../config/Database.php";

if (!isset($_GET['coach_id'])) {
    die("Coach not found");
}

$coachId = (int)$_GET['coach_id'];

$pdo = Database::connect();

$sql = "SELECT id, date_seance, heure, duree
        FROM seances
        WHERE coach_id = ? AND statut = 'disponible'
        ORDER BY date_seance, heure";

$stmt = $pdo->prepare($sql);
$stmt->execute([$coachId]);
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Book a session</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Available sessions</h2>

<?php if (empty($seances)): ?>
  <p>Aucune disponibilité pour ce coach.</p>
<?php else: ?>
  <?php foreach ($seances as $s): ?>
    <form method="post" action="../app/actions/book_seance.php" style="margin-bottom:10px;">
      <input type="hidden" name="seance_id" value="<?= (int)$s['id'] ?>">

      <div class="card">
        <strong><?= htmlspecialchars($s['date_seance']) ?></strong>
        -
        <?= htmlspecialchars(substr($s['heure'], 0, 5)) ?>
        (<?= (int)$s['duree'] ?> min)

        <br><br>

        <button type="submit" class="btn primary sm">
          Reserve
        </button>
      </div>
    </form>
  <?php endforeach; ?>
<?php endif; ?>

<a href="coaches.php">← Back to coaches</a>

</body>
</html>
