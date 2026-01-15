<?php
require_once "Utilisateur.php";

class Sportif extends Utilisateur {
    public function __construct($nom,$prenom,$email,$password){
        parent::__construct($nom,$prenom,$email,$password,"sportif");
    }
    
}
