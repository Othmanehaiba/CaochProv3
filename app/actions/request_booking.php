<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sportif') {
    header("Location: ../view/login.php");
    exit;
}

$sportifId = (int)$_SESSION['user_id'];
$seanceId  = (int)$_POST['seance_id'];

$pdo = Database::connect();

/* Insert REQUEST only */
$sql = "INSERT INTO reservations (seance_id, sportif_id, statut, reserved_at)
        VALUES (?, ?, 'pending', NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([$seanceId, $sportifId]);

header("Location: ../view/dashboard.sportif.php");
exit;