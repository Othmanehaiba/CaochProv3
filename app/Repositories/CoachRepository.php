<?php
class CoachRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function getSportifProfile(int $sportifId): array {
        $sql = "SELECT u.id, u.nom, u.prenom, c.description, c.discipline, c.experience 
                FROM users u JOIN coachs c on u.id = ?
                where u.role = 'coach'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$sportifId]);

        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $user;
    }
}