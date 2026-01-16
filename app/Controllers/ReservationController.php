<?php
require_once __DIR__ . '/../../config/Database.php';

class ReservationController
{
    /**
     * GET  /reserve?coach_id=123  -> show available sessions (view/book.php)
     * POST /reserve              -> reserve a seance (from book.php)
     */
    public function reserve(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../../view/book.php';
            return;
        }

        // POST
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Basic security: only sportif
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'sportif') {
            header('Location: /login');
            exit;
        }

        $sportifId = (int)$_SESSION['user_id'];
        $seanceId  = (int)($_POST['seance_id'] ?? 0);

        if ($seanceId <= 0) {
            header('Location: /');
            exit;
        }

        $pdo = Database::connect();

        try {
            $pdo->beginTransaction();

            // Lock the seance row (avoid double booking)
            $stmt = $pdo->prepare('SELECT statut FROM seances WHERE id = ? FOR UPDATE');
            $stmt->execute([$seanceId]);
            $seance = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$seance || $seance['statut'] !== 'disponible') {
                $pdo->rollBack();
                header('Location: /');
                exit;
            }

            // Insert reservation (pending by default)
            $stmt = $pdo->prepare("INSERT INTO reservations (seance_id, sportif_id, statut, reserved_at) VALUES (?, ?, 'pending', NOW())");
            $stmt->execute([$seanceId, $sportifId]);

            // Mark seance as reserved
            $stmt = $pdo->prepare("UPDATE seances SET statut = 'reservee' WHERE id = ?");
            $stmt->execute([$seanceId]);

            $pdo->commit();

            header('Location: /sportif');
            exit;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            header('Location: /');
            exit;
        }
    }

    /**
     * GET /reservations
     */
    public function myReservations(): void
    {
        require __DIR__ . '/../../view/dashboard.sportif.php';
    }

    /**
     * POST /reservation/cancel
     */
    public function cancel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /sportif');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'sportif') {
            header('Location: /login');
            exit;
        }

        $sportifId = (int)$_SESSION['user_id'];
        $reservationId = (int)($_POST['reservation_id'] ?? 0);

        if ($reservationId <= 0) {
            header('Location: /sportif');
            exit;
        }

        $pdo = Database::connect();

        try {
            $pdo->beginTransaction();

            // Find reservation and ensure it belongs to this sportif
            $stmt = $pdo->prepare('SELECT seance_id FROM reservations WHERE id = ? AND sportif_id = ? FOR UPDATE');
            $stmt->execute([$reservationId, $sportifId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $pdo->rollBack();
                header('Location: /sportif');
                exit;
            }

            $seanceId = (int)$row['seance_id'];

            // Delete reservation
            $stmt = $pdo->prepare('DELETE FROM reservations WHERE id = ?');
            $stmt->execute([$reservationId]);

            // Put seance back to disponible
            $stmt = $pdo->prepare("UPDATE seances SET statut = 'disponible' WHERE id = ?");
            $stmt->execute([$seanceId]);

            $pdo->commit();

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        }

        header('Location: /sportif');
        exit;
    }
}