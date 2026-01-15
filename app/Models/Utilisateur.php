<?php
class Utilisateur {
    protected int $id;
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $password;
    protected string $role;

    public function __construct($nom, $prenom, $email, $password, $role){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
    public function getNom(){return $this->nom;}
    public function getPrenom(){return $this->prenom;}
    public function getEmail(){ return $this->email; }
    public function getPassword(){return $this->password;}
    public function getRole(){ return $this->role; }

    public function setNom($nom){ $this->nom = $nom; }
}
