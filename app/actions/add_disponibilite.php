<?php
require_once "./config/Database.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$pdo = Database::connect();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'coach') {
    header("Location: ../view/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$date = $_POST['date_seance'];
$heure = $_POST['heure'];
$duree = (int)$_POST['duree'];
$statut = $_POST['duree'];


/* Insert REQUEST only */
$sql = "INSERT INTO disponibilite (id_coach, date, heure_debut, duree, statut)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $date, $heure, $duree, $statut]);

header("Location: ../view/dashboard.coach.php");
exit;