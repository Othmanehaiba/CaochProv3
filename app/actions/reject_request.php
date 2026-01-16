<?php
require_once __DIR__ . "/../../config/Database.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     header("Location: /coach/disponibilite");
//     exit;
// }

// $coachId = (int)($_SESSION['user_id'] ?? 0);
$reservationId = (int)($_POST['reservation_id'] ?? 0);

// if ($coachId <= 0 || $reservationId <= 0) {
//     header("Location: /coach/disponibilite");
//     exit;
// }

$pdo = Database::connect();

// try {
//     $pdo->beginTransaction();

    // $sql = "SELECT r.seance_id, s.coach_id, s.statut AS seance_statut
    //         FROM reservations r
    //         JOIN seances s ON s.id = r.seance_id
    //         WHERE r.id = ?
    //         FOR UPDATE";

    // $stmt = $pdo->prepare($sql);
    // $stmt->execute([$reservationId]);
    // $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // if (!$row) {
    //     $pdo->rollBack();
    //     header("Location: /coach/disponibilite");
    //     exit;
    // }

    // if ((int)$row['coach_id'] !== $coachId) {
    //     $pdo->rollBack();
    //     header("Location: /coach/disponibilite");
    //     exit;
    // }

    // Reject request
    $stmt = $pdo->prepare("UPDATE reservations SET statut = 'rejected' WHERE id = ?");
    $stmt->execute([$reservationId]);

    // Keep seance available
    // $stmt = $pdo->prepare("UPDATE seances SET statut = 'disponible' WHERE id = ?");
    // $stmt->execute([(int)$row['seance_id']]);

    // $pdo->commit();

// } catch (Exception $e) {
//     if ($pdo->inTransaction()) {
//         $pdo->rollBack();
//     }
// }

header("Location: /coach/disponibilite");
exit;