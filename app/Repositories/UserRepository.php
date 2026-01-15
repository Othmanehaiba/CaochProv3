<?php
require_once __DIR__ . "/../../config/Database.php";

class UserRepository {
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function createCoach($coach): bool {
       $sqlUser = "INSERT INTO users (nom, prenom, email, pass, role)
                    VALUES (?, ?, ?, ?, 'coach')";
        $stmtUser = $this->pdo->prepare($sqlUser);

        $okUser = $stmtUser->execute([
            $coach->getNom(),
            $coach->getPrenom(),
            $coach->getEmail(),
            $coach->getPassword()   
        ]);

        if (!$okUser) {
            return false;
        }

        $userId = $this->pdo->lastInsertId();

        $sqlCoach = "INSERT INTO coachs (user_id, discipline, experience, description)
                     VALUES (?, ?, ?, ?)";
        $stmtCoach = $this->pdo->prepare($sqlCoach);

        return $stmtCoach->execute([
            $userId,
            $coach->getDiscipline(),
            $coach->getExperience(),
            $coach->getDescription()
        ]);
    }

    public function createSportif($sportif): bool {
        $sql = "INSERT INTO users (nom, prenom, email, pass, role)
                VALUES (?, ?, ?, ?, 'sportif')";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $sportif->getNom(),
            $sportif->getPrenom(),
            $sportif->getEmail(),
            $sportif->getPassword()
        ]);
    }

    public function checkLogin(string $email, string $password, string $role): array
{
    $sql = "SELECT * FROM users WHERE email = ? AND role = ? AND pass = ? LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$email, $role, $password]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}


    public function getAllProfiles(): array {

            $sql = "SELECT 
                      u.id, u.nom, u.prenom, u.email, u.role,
                      c.discipline, c.experience
                    FROM users u
                    LEFT JOIN coachs c ON c.user_id = u.id
                    ORDER BY u.id ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $userId): bool{
    // If coachs table uses FK to users.id, delete child row first
    // $stmt = $this->pdo->prepare("DELETE FROM coachs WHERE user_id = ?");
    // $stmt->execute([$userId]);

    // If sportifs table exists (optional), delete child row too
    // $stmt = $this->pdo->prepare("DELETE FROM sportifs WHERE user_id = ?");
    // $stmt->execute([$userId]);

    // Finally delete from users
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$userId]);

    }


}
