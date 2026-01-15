<?php
require_once __DIR__ . "/../Repositories/ReservationRepository.php";

class SportifController{
    
    public function afficherMesSeances(int $sportifId): array
    {
        $repo = new ReservationRepository();
        return $repo->getSeancesBySportif($sportifId);
    }
}