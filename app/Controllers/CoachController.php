<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../Repositories/CoachRepository.php";
require_once __DIR__ . "/../Repositories/ReservationRepository.php";

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
        $repo = new CoachRepository();
        return $repo->getSportifProfile($id);
    }

    public function afficherDemandes(int $coachId): array{
        $repo = new ReservationRepository();

        // ⬇️ this returns the array
        return $repo->getRequestsForCoach($coachId);
    } 

    public function coach(): void
    {
        require __DIR__ . "/../../view/coaches.php";
    }

    public function disponibilite(): void
    {
        require __DIR__ . "/../../view/dashboard.coach.php";
    }

    public function addDisponibilite(): void
    {
        require_once __DIR__ . "/../actions/add_disponibilite.php";
    }

    public function deleteDisponibilite(): void
    {
        http_response_code(501);
        echo "Not implemented.";
    }

    public function acceptReservation(): void
    {
        require __DIR__ . "/../actions/accept_request.php";
    }

    public function refuseReservation(): void
    {
        require __DIR__ . "/../actions/reject_request.php";
    }
}
