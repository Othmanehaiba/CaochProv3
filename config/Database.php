<?php
class Database {
    private static ?PDO $pdo = null;

    public static function connect(): PDO {
        if (self::$pdo === null) {
            $host = "localhost";
            $port = "5432";
            $dbname = "coachprov3";   
            $user = "postgres";          
            $password = "Root@123";       

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            self::$pdo = new PDO($dsn, $user, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$pdo;
    }
}

// try {
//     $db = Database::connect();
//     echo "PostgreSQL connected successfully!";
// } catch (PDOException $e) {
//     echo "Connection error: " . $e->getMessage();
// }
