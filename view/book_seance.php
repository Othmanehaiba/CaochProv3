<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une Séance</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in as sportif
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sportif') {
        header('Location: /login');
        exit;
    }

    require_once __DIR__ . '/../app/Repositories/SeanceRepository.php';

    $seanceRepo = new SeanceRepository();
    $coachId = isset($_GET['coach_id']) ? (int)$_GET['coach_id'] : null;

    // Get available seances
    if ($coachId) {
        $availableSeances = $seanceRepo->getAvailableByCoachId($coachId);
        $title = "Séances disponibles pour ce coach";
    } else {
        $availableSeances = $seanceRepo->getAllAvailable();
        $title = "Toutes les séances disponibles";
    }
    ?>

    <div class="container">
        <nav class="navbar">
            <h2>Sportif Dashboard</h2>
            <div class="nav-links">
                <a href="/sportif">Mes Réservations</a>
                <a href="/coach">Voir les Coachs</a>
                <a href="/reserve">Réserver une Séance</a>
                <a href="/logout">Déconnexion</a>
            </div>
        </nav>

        <div class="dashboard">
            <h1><?= htmlspecialchars($title) ?></h1>
            
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

            <?php if (empty($availableSeances)): ?>
                <div class="card">
                    <p>Aucune séance disponible pour le moment.</p>
                    <a href="/coach" class="btn btn-primary">Voir tous les coachs</a>
                </div>
            <?php else: ?>
                <div class="seances-grid">
                    <?php foreach ($availableSeances as $seance): ?>
                        <div class="seance-card">
                            <?php if (isset($seance['coach_nom'])): ?>
                                <div class="coach-info">
                                    <h3><?= htmlspecialchars($seance['coach_nom']) ?> <?= htmlspecialchars($seance['coach_prenom']) ?></h3>
                                    <p class="discipline"><?= htmlspecialchars($seance['discipline']) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="seance-details">
                                <div class="detail-row">
                                    <span class="icon">📅</span>
                                    <span><?= date('d/m/Y', strtotime($seance['date_seance'])) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="icon">🕐</span>
                                    <span><?= date('H:i', strtotime($seance['heure'])) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="icon">⏱️</span>
                                    <span><?= $seance['duree'] ?> minutes</span>
                                </div>
                            </div>

                            <form action="/reserve" method="POST" class="booking-form">
                                <input type="hidden" name="seance_id" value="<?= $seance['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-block">Réserver cette séance</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar h2 {
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background: #34495e;
        }

        .dashboard {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dashboard h1 {
            margin-bottom: 30px;
            color: #2c3e50;
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

        .card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .seances-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .seance-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .seance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .coach-info {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .coach-info h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .discipline {
            color: #3498db;
            font-weight: 600;
            font-size: 14px;
        }

        .seance-details {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #555;
        }

        .detail-row .icon {
            font-size: 20px;
            margin-right: 10px;
        }

        .booking-form {
            margin-top: 15px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s, transform 0.1s;
            text-decoration: none;
            display: inline-block;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-block {
            width: 100%;
            text-align: center;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .seances-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Optional: Add confirmation before booking
        document.querySelectorAll('.booking-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Voulez-vous vraiment réserver cette séance?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>