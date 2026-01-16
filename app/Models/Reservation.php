<?php

class Reservation
{
    private ?int $id;
    private int $seance_id;
    private int $sportif_id;
    private int $coach_id;
    private string $statut; // 'pending', 'accepted', 'rejected'
    private ?string $reserved_at;

    public function __construct(
        int $seance_id,
        int $sportif_id,
        int $coach_id,
        string $statut = 'pending',
        ?int $id = null,
        ?string $reserved_at = null
    ) {
        $this->seance_id = $seance_id;
        $this->sportif_id = $sportif_id;
        $this->coach_id = $coach_id;
        $this->statut = $statut;
        $this->id = $id;
        $this->reserved_at = $reserved_at;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeanceId(): int
    {
        return $this->seance_id;
    }

    public function getSportifId(): int
    {
        return $this->sportif_id;
    }

    public function getCoachId(): int
    {
        return $this->coach_id;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getReservedAt(): ?string
    {
        return $this->reserved_at;
    }

    // Setters
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->statut === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->statut === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->statut === 'rejected';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'seance_id' => $this->seance_id,
            'sportif_id' => $this->sportif_id,
            'coach_id' => $this->coach_id,
            'statut' => $this->statut,
            'reserved_at' => $this->reserved_at,
        ];
    }
}