<?php
require_once __DIR__ . "/../Repositories/ReservationRepository.php";
require_once __DIR__ . "/../Repositories/CoachRepository.php";

class SportifController{
    
    public function afficherMesSeances(int $sportifId): array
    {
        $repo = new ReservationRepository();
        return $repo->getSeancesBySportif($sportifId);
    }

    public function afficherProfile(int $sportifId): array
    {
        $repo = new CoachRepository();
        return $repo->getSportifProfile($sportifId);
    }

    public function sportif(): void
    {
        require __DIR__ . "/../../view/dashboard.sportif.php";
    }

    public function details(): void
    {
        require __DIR__ . "/../../view/profil.sportif.php";
    }
}
