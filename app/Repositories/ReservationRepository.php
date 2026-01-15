<?php
require_once __DIR__ . "/../../config/Database.php";

class ReservationRepository {

    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function getSeancesBySportif(int $sportifId): array{
        $sql = "SELECT
                  r.id            AS reservation_id,
                  r.reserved_at   AS reserved_at,
                  s.date_seance,
                  s.heure,
                  s.duree,
                  s.statut        AS seance_statut,
                  u.nom           AS coach_nom,
                  u.prenom        AS coach_prenom
                FROM reservations r
                JOIN seances s ON s.id = r.seance_id
                JOIN users  u ON u.id = s.coach_id
                WHERE r.sportif_id = ?
                ORDER BY s.date_seance DESC, s.heure DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$sportifId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRequestsForCoach(int $coachId): array
{
        $sql = "SELECT
                  r.id AS reservation_id,
                  u.nom,
                  u.prenom,
                  s.date_seance,
                  s.heure
                FROM reservations r
                JOIN seances s ON s.id = r.seance_id
                JOIN users u ON u.id = r.sportif_id
                WHERE s.coach_id = ?
                AND r.statut = 'pending'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$coachId]);

        // ⬇️ THIS creates the array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getReservationsForCoach(int $coachId): array{
    $sql = "SELECT
              r.id AS reservation_id,
              r.reserved_at,
              s.id AS seance_id,
              s.date_seance,
              s.heure,
              s.duree,
              u.nom AS sportif_nom,
              u.prenom AS sportif_prenom
            FROM reservations r
            JOIN seances s ON s.id = r.seance_id
            JOIN users u ON u.id = r.sportif_id
            WHERE s.coach_id = ?
            ORDER BY s.date_seance DESC, s.heure DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$coachId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}