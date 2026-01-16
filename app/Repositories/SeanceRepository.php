<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Models/Seance.php';

class SeanceRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    
    public function create(Seance $seance): ?int
    {
        $sql = "INSERT INTO seances (coach_id, date_seance, heure, duree, statut, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $seance->getCoachId(),
            $seance->getDateSeance(),
            $seance->getHeure(),
            $seance->getDuree(),
            $seance->getStatut()
        ]);

        return (int)$this->pdo->lastInsertId() ?: null;
    }

    
    public function findById(int $id): ?Seance
    {
        $sql = "SELECT * FROM seances WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    
    public function findByCoachId(int $coachId): array
    {
        $sql = "SELECT * FROM seances WHERE coach_id = ? ORDER BY date_seance DESC, heure DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$coachId]);
        
        $seances = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $seances[] = $this->hydrate($row);
        }

        return $seances;
    }

    
    public function getAvailableByCoachId(int $coachId): array
    {
        $sql = "SELECT * FROM seances 
                WHERE coach_id = ?
                AND statut = 'disponible'
                AND date_seance >= CURDATE()
                ORDER BY date_seance ASC, heure ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$coachId]);
        
        $seances = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $seances[] = $this->hydrate($row);
        }

        return $seances;
    }

   
    public function getAllAvailable(): array
    {
        $sql = "SELECT s.*, u.nom as coach_nom, u.prenom as coach_prenom, c.discipline
                FROM seances s
                JOIN coachs c ON s.coach_id = c.user_id
                JOIN users u ON c.user_id = u.id
                WHERE s.statut = 'disponible'
                AND s.date_seance >= CURDATE()
                ORDER BY s.date_seance ASC, s.heure ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function update(Seance $seance): bool
    {
        $sql = "UPDATE seances 
                SET date_seance = ?,
                    heure = ?,
                    duree = ?,
                    statut = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $seance->getDateSeance(),
            $seance->getHeure(),
            $seance->getDuree(),
            $seance->getStatut(),
            $seance->getId(),
        ]);
    }

    /**
     * Update seance status
     */
    public function updateStatus(int $id, string $statut): bool
    {
        $sql = "UPDATE seances SET statut = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $statut,
            $id,
        ]);
    }

    /**
     * Delete seance
     */
    public function delete(int $id): bool
    {
        // Check if seance has reservations
        $checkSql = "SELECT COUNT(*) FROM reservations WHERE seance_id = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$id]);
        
        if ($checkStmt->fetchColumn() > 0) {
            // Don't delete if there are reservations, just mark as cancelled
            return $this->updateStatus($id, 'annulee');
        }

        $sql = "DELETE FROM seances WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Check if a seance time slot is available for a coach
     */
    public function isTimeSlotAvailable(int $coachId, string $date, string $heure, int $duree, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM seances 
                WHERE coach_id = ?
                AND date_seance = ?
                AND statut != 'annulee'
                AND id != ?
                AND (
                    (heure < :heure_end AND ADDTIME(heure, SEC_TO_TIME(duree * 60)) > :heure)
                )";
        
        $stmt = $this->pdo->prepare($sql);
        $heureEnd = date('H:i:s', strtotime($heure) + ($duree * 60));
        
        $stmt->execute([
            $coachId,
            $date,
            $excludeId ?? 0,
        ]);

        return $stmt->fetchColumn() == 0;
    }

    /**
     * Hydrate array to Seance object
     */
    private function hydrate(array $row): Seance
    {
        return new Seance(
            (int)$row['coach_id'],
            $row['date_seance'],
            $row['heure'],
            (int)$row['duree'],
            $row['statut'],
            (int)$row['id'],
            $row['created_at']
        );
    }
}