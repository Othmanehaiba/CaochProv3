<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../Repositories/UserRepository.php";
require_once __DIR__ . "/../Models/Coach.php";
require_once __DIR__ . "/../Models/Sportif.php";

class AuthController {

    public function register(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["submit"])) {
            return;
        }

        $role = $_POST["role"] ?? "";

        $repo = new UserRepository();

        if ($role === "coach") {
            $coach = new Coach(
                $_POST["nom"] ?? "",
                $_POST["prenom"] ?? "",
                $_POST["email"] ?? "",
                $_POST["password"] ?? "",
                $_POST["discipline"] ?? "",
                (int)($_POST["experience"] ?? 0),
                $_POST["description"] ?? ""
            );

            $repo->createCoach($coach);
            header("Location: /login");
            exit;
        }

        if ($role === "sportif") {
            $sportif = new Sportif(
                $_POST["nom"] ?? "",
                $_POST["prenom"] ?? "",
                $_POST["email"] ?? "",
                $_POST["password"] ?? ""
            );
            $repo->createSportif($sportif);
            header("Location: /login");
            exit;
        }
    }

    public function signup(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            require __DIR__ . "/../../view/register.php";
            return;
        }

        $this->register();
    }

    public function login(): void
    {

        // die("OKK");
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            require __DIR__ . "/../../view/login.php";
            return;
        }

        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if ($email === "" || $password === "") {
            header("Location: /login");
            exit;
        }

        $repo = new UserRepository();
        $user = $repo->checkLogin($email, $password);

        if (!$user) {
            header("Location: /login");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int)$user["id"];
        // $_SESSION["id"] = $userId;
        // $_SESSION["id_user"] = $userId;
        $_SESSION["user_id"] = $userId;
        $_SESSION["role"] = $user["role"];
        $_SESSION["nom"] = $user["nom"];
        $_SESSION["prenom"] = $user["prenom"];

        if ($user["role"] === "coach") {
            header("Location: /coach/disponibilite");
            exit;
        } elseif ($user["role"] === "sportif") {
            header("Location: /sportif");
            exit;
        }

        header("Location: /view/dashboard.admin.php");
        exit;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: /login");
        exit;
    }
}

if (basename(__FILE__) === basename($_SERVER["SCRIPT_FILENAME"] ?? "")) {
    $controller = new AuthController();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $action = $_POST["action"] ?? "";

        if ($action === "login") {
            $controller->login();
        } elseif ($action === "register") {
            $controller->register();
        }
    }
}