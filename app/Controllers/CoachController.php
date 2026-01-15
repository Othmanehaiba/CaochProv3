<?php
require_once __DIR__ . "/../../config/Database.php";

class CoachController {

    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function afficherCoaches(){
        $sql = "SELECT u.id, u.nom, u.prenom, c.description, c.discipline, c.experience 
                FROM users u JOIN coachs c on u.id = c.user_id
                where u.role = ? ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['coach']);

        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $user;        
    }

    public function afficherProfile($id){
        $sql = "SELECT u.id, u.nom, u.prenom, c.description, c.discipline, c.experience 
                FROM users u JOIN coachs c on u.id = ?
                where u.role = ''coach ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $user;
    }

    public function afficherDemandes(int $coachId): array{
        $repo = new ReservationRepository();

        // ⬇️ this returns the array
        return $repo->getRequestsForCoach($coachId);
    } 
}