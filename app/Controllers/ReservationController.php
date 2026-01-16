<?php
require_once __DIR__ . '/../../config/Database.php';

class ReservationController
{
    
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
        // if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'sportif') {
        //     header('Location: /login');
        //     exit;
        // }

        $sportifId = (int)$_SESSION['user_id'];
        $dispoId  = (int)($_POST['disponibilite_id']);

        if ($dispoId <= 0) {
            header('Location: /');
            exit;
        }

        $pdo = Database::connect();

        // try {
        //     $pdo->beginTransaction();

            $stmt = $pdo->prepare("
            SELECT id, id_coach, date, heure_debut, duree, statut
            FROM disponibilite
            WHERE id = ?
            FOR UPDATE
        ");
        $stmt->execute([$dispoId]);
        $dispo = $stmt->fetch(PDO::FETCH_ASSOC);

            // if (!$dispo || $dispo['statut'] !== 'disponible') {
            //     $pdo->rollBack();
            //     header('Location: /');
            //     exit;
            // }

            $coachId = (int)$dispo['id_coach'];

        $start = new DateTimeImmutable($dispo['date'] . ' ' . $dispo['heure_debut']);
        $end  = $start->modify('+' . (int)$dispo['duree'] . ' minutes');
        $heureFin = $end->format('H:i:s');

            $stmt = $pdo->prepare("
            INSERT INTO reservation
              (id_client, id_coach, id_disponibilite, date, heure_debut, heure_fin, objectif, statut)
            VALUES
              (?, ?, ?, ?, ?, ?, ?, 'en_attente')
        ");
        $stmt->execute([
            $sportifId,
            $coachId,
            $dispoId,
            $dispo['date'],
            $dispo['heure_debut'],
            $heureFin,
            $_POST['objectif'] ?? null
        ]);

        $stmt = $pdo->prepare("UPDATE disponibilite SET statut = 'en_attente' WHERE id = ?");
        $stmt->execute([$dispoId]);

            

            // $pdo->commit();

            header('Location: /sportif');
            exit;

        // } catch (Exception $e) {
        //     if ($pdo->inTransaction()) {
        //         $pdo->rollBack();
        //     }
        //     die("NOKKK");
        //     header('Location: /');
        //     exit;
        // }
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