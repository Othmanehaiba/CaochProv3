<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
require_once __DIR__ . "/../Controllers/AdminController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../view/dashboard.admin.php");
    exit;
}

$userId = (int)($_POST['user_id'] ?? 0);
if ($userId <= 0) {
    header("Location: ../../view/dashboard.admin.php");
    exit;
}

$admin = new AdminController();
$admin->deleteUser($userId);

header("Location: ../../view/dashboard.admin.php");
exit;
