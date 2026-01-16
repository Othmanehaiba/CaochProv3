<?php

class Seance
{
    private ?int $id;
    private int $coach_id;
    private string $date_seance;
    private string $heure;
    private int $duree; // duration in minutes
    private string $statut; // 'disponible', 'reservee', 'annulee'
    private ?string $created_at;

    public function __construct(
        int $coach_id,
        string $date_seance,
        string $heure,
        int $duree,
        string $statut = 'disponible',
        ?int $id = null,
        ?string $created_at = null
    ) {
        $this->coach_id = $coach_id;
        $this->date_seance = $date_seance;
        $this->heure = $heure;
        $this->duree = $duree;
        $this->statut = $statut;
        $this->id = $id;
        $this->created_at = $created_at;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoachId(): int
    {
        return $this->coach_id;
    }

    public function getDateSeance(): string
    {
        return $this->date_seance;
    }

    public function getHeure(): string
    {
        return $this->heure;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDateSeance(string $date_seance): void
    {
        $this->date_seance = $date_seance;
    }

    public function setHeure(string $heure): void
    {
        $this->heure = $heure;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    // Helper methods
    public function isAvailable(): bool
    {
        return $this->statut === 'disponible';
    }

    public function isReserved(): bool
    {
        return $this->statut === 'reservee';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'coach_id' => $this->coach_id,
            'date_seance' => $this->date_seance,
            'heure' => $this->heure,
            'duree' => $this->duree,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
        ];
    }
}