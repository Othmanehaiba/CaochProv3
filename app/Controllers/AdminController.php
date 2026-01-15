<?php
require_once __DIR__ . "/../Repositories/UserRepository.php";

class AdminController
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function afficherProfiles(): array
    {
        return $this->repo->getAllProfiles();
    }

    public function deleteUser(int $userId): bool
    {
        return $this->repo->deleteById($userId);
    }
}
