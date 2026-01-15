<?php
session_start();
require_once __DIR__ . "/../../config/Database.php";

// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'sportif') {
//     header("Location: ../../view/login.php");
//     exit;
// }

$sportifId = (int)$_SESSION['user_id'];
$seanceId  = (int)($_POST['seance_id'] ?? 0);

if ($seanceId <= 0) {
    header("Location: ../../view/coaches.php");
    exit;
}

$pdo = Database::connect();

try {
    $pdo->beginTransaction();

    // 1) lock the seance row
    $stmt = $pdo->prepare("SELECT statut FROM seances WHERE id = ? FOR UPDATE");
    $stmt->execute([$seanceId]);
    $seance = $stmt->fetch(PDO::FETCH_ASSOC);

    // if not found or not disponible -> refuse
    if (!$seance || $seance['statut'] !== 'disponible') {
        $pdo->rollBack();
        header("Location: ../../view/coaches.php");
        exit;
    }

    // 2) insert reservation
    $stmt = $pdo->prepare("INSERT INTO reservations (seance_id, sportif_id, reserved_at) VALUES (?, ?, NOW())");
    $stmt->execute([$seanceId, $sportifId]);

    // 3) update seance status to reservee
    $stmt = $pdo->prepare("UPDATE seances SET statut = 'reservee' WHERE id = ?");
    $stmt->execute([$seanceId]);

    $pdo->commit();

    header("Location: ../../view/dashboard.sportif.php");
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    header("Location: ../../view/coaches.php");
    exit;
}
