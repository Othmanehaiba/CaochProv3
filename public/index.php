<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controllerFiles = [
    'HomeController' => __DIR__ . '/../app/Controllers/HomeController.php',
    'AuthController' => __DIR__ . '/../app/Controllers/AuthController.php',
    'CoachController' => __DIR__ . '/../app/Controllers/CoachController.php',
    'SportifController' => __DIR__ . '/../app/Controllers/SportifController.php',
    'ReservationController' => __DIR__ . '/../app/Controllers/ReservationController.php',
    'ErrorController' => __DIR__ . '/../app/Controllers/ErrorController.php',
];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($base !== '' && strpos($path, $base) === 0) {
    $path = substr($path, strlen($base));
}

$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

// routes
$routes = [
    '/' => ['HomeController', 'index'],

    // Auth routes
    '/login' => ['AuthController', 'login'],
    '/signup' => ['AuthController', 'signup'],
    '/logout' => ['AuthController', 'logout'],

    // Coach routes
    '/coach' => ['CoachController', 'coach'],
    '/coach/disponibilite' => ['CoachController', 'disponibilite'],
    '/coach/addDisponibilite' => ['CoachController', 'addDisponibilite'],
    '/coach/deleteDisponibilite' => ['CoachController', 'deleteDisponibilite'],
    '/coach/acceptReservation' => ['CoachController', 'acceptReservation'],
    '/coach/refuseReservation' => ['CoachController', 'refuseReservation'],

    // Sportif routes
    '/sportif' => ['SportifController', 'sportif'],
    '/sportif/details' => ['SportifController', 'details'],

    // Reservation routes
    '/reserve' => ['ReservationController', 'reserve'],
    '/reservations' => ['ReservationController', 'myReservations'],
    '/reservation/cancel' => ['ReservationController', 'cancel'],

    '/error' => ['ErrorController', 'error'],
];

if (isset($routes[$path])) {
    [$controller, $method] = $routes[$path];

    if (isset($controllerFiles[$controller]) && is_file($controllerFiles[$controller])) {
        require_once $controllerFiles[$controller];
    }

    if (!class_exists($controller)) {
        http_response_code(500);
        echo "Controller not found.";
        exit;
    }

    $controllerObj = new $controller();

    if (method_exists($controllerObj, $method)) {
        $controllerObj->$method();
    } else {
        http_response_code(500);
        echo "Method not found.";
    }
} else {
    echo "NOKK";
    http_response_code(404);
    echo "Page not found.";
}