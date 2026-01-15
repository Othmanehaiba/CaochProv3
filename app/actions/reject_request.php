<?php
session_start();
require_once __DIR__ . "/../../config/Database.php";

/* 1) Must be a logged-in coach */
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'coach') {
//     header("Location: ../../view/login.php");
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../view/dashboard.coach.php");
    exit;
}

$coachId = (int)$_SESSION['user_id'];
$reservationId = (int)($_POST['reservation_id'] ?? 0);

if ($reservationId <= 0) {
    header("Location: ../../view/dashboard.coach.php");
    exit;
}
// die($_POST['reservation_id']);
$pdo = Database::connect();



    /* 2) Get reservation + seance, and lock rows to prevent conflicts */
    $sql = "SELECT r.seance_id, s.coach_id, s.statut
            FROM reservations r
            JOIN seances s ON s.id = r.seance_id
            WHERE r.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reservationId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    /* 3) Check reservation exists */
    // if (!$row) {
    //     $pdo->rollBack();
    //     header("Location: ../../view/dashboard.coach.php");
    //     exit;
    // }

    // /* 4) Check this seance belongs to this coach */
    // if ((int)$row['coach_id'] !== $coachId) {
    //     $pdo->rollBack();
    //     header("Location: ../../view/dashboard.coach.php");
    //     exit;
    // }

    // /* 5) Check seance still available */
    // if ($row['statut'] !== 'disponible') {
    //     $pdo->rollBack();
    //     header("Location: ../../view/dashboard.coach.php");
    //     exit;
    // }

    /* 6) Accept = mark seance as reserved */
    $stmt = $pdo->prepare("UPDATE reservations SET statut = 'rejected' WHERE id = ?");
    $stmt->execute([(int)$row['seance_id']]);


    


    header("Location: ../../view/dashboard.coach.php");
    exit;

