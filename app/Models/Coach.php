<?php
require_once "Utilisateur.php";

class Coach extends Utilisateur {
    private string $discipline;
    private int $experience;
    private string $description;

    public function __construct($nom,$prenom,$email,$password,$discipline,$experience,$description){
        parent::__construct($nom,$prenom,$email,$password,"coach");
        $this->discipline = $discipline;
        $this->experience = $experience;
        $this->description = $description;
    }

    public function getDiscipline(){ return $this->discipline; }
    public function getExperience(){ return $this->experience;}
    public function getDescription(){ return $this->description;}
}
 