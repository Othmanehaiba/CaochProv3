<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Disponibilités - Coach</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in as coach
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'coach') {
        header('Location: /login');
        exit;
    }

    require_once __DIR__ . '/../app/Repositories/SeanceRepository.php';
    require_once __DIR__ . '/../app/Repositories/ReservationRepository.php';

    $coachId = (int)$_SESSION['user_id'];
    $seanceRepo = new SeanceRepository();
    $reservationRepo = new ReservationRepository();

    // Get all seances for this coach
    $seances = $seanceRepo->findByCoachId($coachId);

    // Get pending reservations
    $pendingReservations = $reservationRepo->getRequestsForCoach($coachId);
    ?>

    <div class="container">
        <nav class="navbar">
            <h2>Coach Dashboard</h2>
            <div class="nav-links">
                <a href="/coach/disponibilite">Mes Séances</a>
                <a href="/coach">Liste Coachs</a>
                <a href="/logout">Déconnexion</a>
            </div>
        </nav>

        <div class="dashboard">
            <h1>Gérer mes Disponibilités</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <!-- Add New Seance Form -->
            <div class="card">
                <h2>Créer une nouvelle séance</h2>
                <form action="/coach/addDisponibilite" method="POST" class="seance-form">
                    <div class="form-group">
                        <label for="date_seance">Date de la séance *</label>
                        <input 
                            type="date" 
                            id="date_seance" 
                            name="date_seance" 
                            min="<?= date('Y-m-d') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="heure">Heure de début *</label>
                        <input 
                            type="time" 
                            id="heure" 
                            name="heure" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="duree">Durée (minutes) *</label>
                        <select id="duree" name="duree" required>
                            <option value="30">30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60" selected>1 heure</option>
                            <option value="90">1h30</option>
                            <option value="120">2 heures</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Créer la séance</button>
                </form>
            </div>

            <!-- Pending Reservations -->
            <?php if (!empty($pendingReservations)): ?>
            <div class="card">
                <h2>Demandes de réservation en attente (<?= count($pendingReservations) ?>)</h2>
                <div class="reservations-list">
                    <?php foreach ($pendingReservations as $reservation): ?>
                        <div class="reservation-item pending">
                            <div class="reservation-info">
                                <p><strong>Sportif:</strong> <?= htmlspecialchars($reservation['sportif_nom'] ?? 'N/A') ?></p>
                                <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($reservation['date_seance'])) ?></p>
                                <p><strong>Heure:</strong> <?= date('H:i', strtotime($reservation['heure'])) ?></p>
                                <p><strong>Durée:</strong> <?= $reservation['duree'] ?> min</p>
                            </div>
                            <div class="reservation-actions">
                                <form action="/coach/acceptReservation" method="POST" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                    <button type="submit" class="btn btn-success">Accepter</button>
                                </form>
                                <form action="/coach/refuseReservation" method="POST" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Refuser</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- List of Seances -->
            <div class="card">
                <h2>Mes Séances</h2>
                
                <?php if (empty($seances)): ?>
                    <p>Vous n'avez pas encore créé de séances.</p>
                <?php else: ?>
                    <div class="seances-list">
                        <?php foreach ($seances as $seance): ?>
                            <?php
                            $statusClass = '';
                            $statusText = '';
                            switch ($seance->getStatut()) {
                                case 'disponible':
                                    $statusClass = 'status-available';
                                    $statusText = 'Disponible';
                                    break;
                                case 'reservee':
                                    $statusClass = 'status-reserved';
                                    $statusText = 'Réservée';
                                    break;
                                case 'annulee':
                                    $statusClass = 'status-cancelled';
                                    $statusText = 'Annulée';
                                    break;
                            }
                            ?>
                            <div class="seance-item <?= $statusClass ?>">
                                <div class="seance-info">
                                    <h3><?= date('d/m/Y', strtotime($seance->getDateSeance())) ?></h3>
                                    <p><strong>Heure:</strong> <?= date('H:i', strtotime($seance->getHeure())) ?></p>
                                    <p><strong>Durée:</strong> <?= $seance->getDuree() ?> minutes</p>
                                    <p><strong>Statut:</strong> <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span></p>
                                </div>
                                
                                <?php if ($seance->getStatut() === 'disponible'): ?>
                                    <div class="seance-actions">
                                        <form action="/coach/deleteDisponibilite" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette séance?');">
                                            <input type="hidden" name="seance_id" value="<?= $seance->getId() ?>">
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .navbar {
            background: #333;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background: #555;
        }

        .dashboard {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            background: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .seance-form {
            display: grid;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .seances-list,
        .reservations-list {
            display: grid;
            gap: 15px;
        }

        .seance-item,
        .reservation-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 2px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-available {
            border-color: #28a745;
        }

        .status-reserved {
            border-color: #ffc107;
        }

        .status-cancelled {
            border-color: #dc3545;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .status-badge.status-available {
            background: #28a745;
            color: white;
        }

        .status-badge.status-reserved {
            background: #ffc107;
            color: #333;
        }

        .status-badge.status-cancelled {
            background: #dc3545;
            color: white;
        }

        .reservation-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</body>
</html>