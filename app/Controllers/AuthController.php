<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../Repositories/UserRepository.php";
require_once __DIR__ . "/../Models/Coach.php";
require_once __DIR__ . "/../Models/Sportif.php";

class AuthController {

    public function register(){
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])){

            $role = $_POST["role"];

            $repo = new UserRepository();

            if($role === "coach"){
                $coach = new Coach(
                    $_POST["nom"],
                    $_POST["prenom"],
                    $_POST["email"],
                    $_POST["password"],
                    $_POST["discipline"],
                    (int)$_POST["experience"],
                    $_POST["description"]
                );

                $repo->createCoach($coach);
                header("Location: /view/login.php");
                exit;
            }

            if($role === "sportif"){
                $sportif = new Sportif(
                    $_POST["nom"],
                    $_POST["prenom"],
                    $_POST["email"],
                    $_POST["password"]
                );
                $repo->createSportif($sportif);
                header("Location: /view/login.php");
                exit;
            }
        }
    }
    public function login(): void
{
    // if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    //     header("Location: /view/login.php");
    //     exit;
    // }

    $role = $_POST["role"] ;
    $email = $_POST["email"] ;
    $password = $_POST["password"] ;

    $repo = new UserRepository();
    $user = $repo->checkLogin($email, $password, $role);

    // if ($user) {
    //     var_dump($user);
    //     die("user notfo");
       
    // }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION["id"] = (int)$user["id"];
    $_SESSION["role"] = $user["role"];
    $_SESSION["nom"] = $user["nom"];
    $_SESSION["prenom"] = $user["prenom"];

    if ($user["role"] === "coach") {
    header("Location: ./view/dashboard.coach.php");
    exit;
} elseif ($user["role"] === "sportif") {
    header("Location: /view/dashboard.sportif.php");
    exit;
} else {
    header("Location: /view/dashboard.admin.php");
    exit;
}
}

}

    $controller = new AuthController();
    
    // var_dump($_POST);
  
    if ($_POST["action"] === 'login') {
        $controller->login();
    
    } elseif ($_POST["action"] === 'register') {
        $controller->register();
        echo "jdjd";
    }
    